<?php
namespace App\Modules\Survey\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Survey\Models\Survey;
use App\Modules\JenisSurvey\Models\JenisSurvey;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Survey";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Survey::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Survey::survey', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_jenis_survey = JenisSurvey::all()->pluck('jenis_survey','id');
		
		$data['forms'] = array(
			'id_jenis_survey' => ['Jenis Survey', Form::select("id_jenis_survey", $ref_jenis_survey, null, ["class" => "form-control select2"]) ],
			'nama' => ['Nama', Form::text("nama", old("nama"), ["class" => "form-control","placeholder" => ""]) ],
			'koordinat' => ['Koordinat', Form::text("koordinat", old("koordinat"), ["class" => "form-control","placeholder" => ""]) ],
			'panjang' => ['Panjang', Form::text("panjang", old("panjang"), ["class" => "form-control","placeholder" => ""]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Survey::survey_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'id_jenis_survey' => 'required',
			'nama' => 'required',
			'koordinat' => 'required',
			'panjang' => 'required',
			
		]);

		$survey = new Survey();
		$survey->id_jenis_survey = $request->input("id_jenis_survey");
		$survey->nama = $request->input("nama");
		$survey->koordinat = $request->input("koordinat");
		$survey->panjang = $request->input("panjang");
		
		$survey->created_by = Auth::id();
		$survey->save();

		$text = 'membuat '.$this->title; //' baru '.$survey->what;
		$this->log($request, $text, ['survey.id' => $survey->id]);
		return redirect()->route('survey.index')->with('message_success', 'Survey berhasil ditambahkan!');
	}

	public function show(Request $request, Survey $survey)
	{
		$data['survey'] = $survey;

		$text = 'melihat detail '.$this->title;//.' '.$survey->what;
		$this->log($request, $text, ['survey.id' => $survey->id]);
		return view('Survey::survey_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Survey $survey)
	{
		$data['survey'] = $survey;

		$ref_jenis_survey = JenisSurvey::all()->pluck('jenis_survey','id');
		
		$data['forms'] = array(
			'id_jenis_survey' => ['Jenis Survey', Form::select("id_jenis_survey", $ref_jenis_survey, null, ["class" => "form-control select2"]) ],
			'nama' => ['Nama', Form::text("nama", $survey->nama, ["class" => "form-control","placeholder" => "", "id" => "nama"]) ],
			'koordinat' => ['Koordinat', Form::text("koordinat", $survey->koordinat, ["class" => "form-control","placeholder" => "", "id" => "koordinat"]) ],
			'panjang' => ['Panjang', Form::text("panjang", $survey->panjang, ["class" => "form-control","placeholder" => "", "id" => "panjang"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$survey->what;
		$this->log($request, $text, ['survey.id' => $survey->id]);
		return view('Survey::survey_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'id_jenis_survey' => 'required',
			'nama' => 'required',
			'koordinat' => 'required',
			'panjang' => 'required',
			
		]);
		
		$survey = Survey::find($id);
		$survey->id_jenis_survey = $request->input("id_jenis_survey");
		$survey->nama = $request->input("nama");
		$survey->koordinat = $request->input("koordinat");
		$survey->panjang = $request->input("panjang");
		
		$survey->updated_by = Auth::id();
		$survey->save();


		$text = 'mengedit '.$this->title;//.' '.$survey->what;
		$this->log($request, $text, ['survey.id' => $survey->id]);
		return redirect()->route('survey.index')->with('message_success', 'Survey berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$survey = Survey::find($id);
		$survey->deleted_by = Auth::id();
		$survey->save();
		$survey->delete();

		$text = 'menghapus '.$this->title;//.' '.$survey->what;
		$this->log($request, $text, ['survey.id' => $survey->id]);
		return back()->with('message_success', 'Survey berhasil dihapus!');
	}

}

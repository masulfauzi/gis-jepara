<?php
namespace App\Modules\JenisSurvey\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\JenisSurvey\Models\JenisSurvey;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JenisSurveyController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Jenis Survey";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = JenisSurvey::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('JenisSurvey::jenissurvey', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'jenis_survey' => ['Jenis Survey', Form::text("jenis_survey", old("jenis_survey"), ["class" => "form-control","placeholder" => ""]) ],
			'keterangan' => ['Keterangan', Form::text("keterangan", old("keterangan"), ["class" => "form-control","placeholder" => ""]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('JenisSurvey::jenissurvey_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'jenis_survey' => 'required',
			'keterangan' => 'required',
			
		]);

		$jenissurvey = new JenisSurvey();
		$jenissurvey->jenis_survey = $request->input("jenis_survey");
		$jenissurvey->keterangan = $request->input("keterangan");
		
		$jenissurvey->created_by = Auth::id();
		$jenissurvey->save();

		$text = 'membuat '.$this->title; //' baru '.$jenissurvey->what;
		$this->log($request, $text, ['jenissurvey.id' => $jenissurvey->id]);
		return redirect()->route('jenissurvey.index')->with('message_success', 'Jenis Survey berhasil ditambahkan!');
	}

	public function show(Request $request, JenisSurvey $jenissurvey)
	{
		$data['jenissurvey'] = $jenissurvey;

		$text = 'melihat detail '.$this->title;//.' '.$jenissurvey->what;
		$this->log($request, $text, ['jenissurvey.id' => $jenissurvey->id]);
		return view('JenisSurvey::jenissurvey_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, JenisSurvey $jenissurvey)
	{
		$data['jenissurvey'] = $jenissurvey;

		
		$data['forms'] = array(
			'jenis_survey' => ['Jenis Survey', Form::text("jenis_survey", $jenissurvey->jenis_survey, ["class" => "form-control","placeholder" => "", "id" => "jenis_survey"]) ],
			'keterangan' => ['Keterangan', Form::text("keterangan", $jenissurvey->keterangan, ["class" => "form-control","placeholder" => "", "id" => "keterangan"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$jenissurvey->what;
		$this->log($request, $text, ['jenissurvey.id' => $jenissurvey->id]);
		return view('JenisSurvey::jenissurvey_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'jenis_survey' => 'required',
			'keterangan' => 'required',
			
		]);
		
		$jenissurvey = JenisSurvey::find($id);
		$jenissurvey->jenis_survey = $request->input("jenis_survey");
		$jenissurvey->keterangan = $request->input("keterangan");
		
		$jenissurvey->updated_by = Auth::id();
		$jenissurvey->save();


		$text = 'mengedit '.$this->title;//.' '.$jenissurvey->what;
		$this->log($request, $text, ['jenissurvey.id' => $jenissurvey->id]);
		return redirect()->route('jenissurvey.index')->with('message_success', 'Jenis Survey berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$jenissurvey = JenisSurvey::find($id);
		$jenissurvey->deleted_by = Auth::id();
		$jenissurvey->save();
		$jenissurvey->delete();

		$text = 'menghapus '.$this->title;//.' '.$jenissurvey->what;
		$this->log($request, $text, ['jenissurvey.id' => $jenissurvey->id]);
		return back()->with('message_success', 'Jenis Survey berhasil dihapus!');
	}

}

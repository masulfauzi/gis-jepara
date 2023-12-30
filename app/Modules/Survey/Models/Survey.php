<?php

namespace App\Modules\Survey\Models;

use App\Helpers\UsesUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\JenisSurvey\Models\JenisSurvey;


class Survey extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'survey';
	protected $fillable   = ['*'];	

	public function jenisSurvey(){
		return $this->belongsTo(JenisSurvey::class,"id_jenis_survey","id");
	}

}

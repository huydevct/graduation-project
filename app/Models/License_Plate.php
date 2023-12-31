<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ngocnm\LaravelHelpers\eloquent\BaseModel;

class License_Plate extends Model
{
    use HasFactory, BaseModel;

    protected $table = 'license_plates';

    protected $fillable = [
        'queue_id',
        'title',
        'lps',
        'created_at',
        'updated_at'
    ];


    static $schema = [
        "id" => [
            "type" => "int",
            "insert" => false,
            "query_condition" => true,
            "sort" => true
        ],
        "title" => [
            "type" => "string",
            "insert" => true,
            "query_condition" => true,
            "sort" => true
        ],
        "lps" => [
            "type" => "string",
            "insert" => true,
            "query_condition" => true,
            "sort" => true
        ],
        "queue_id" => [
            "type" => "int",
            "insert" => true,
            "query_condition" => true,
            "sort" => true
        ],
        "created_at" => [
            "type" => "string",
            "insert" => false,
            "query_condition" => false,
            "required_when_create" => false,
            "sort" => true
        ],
        "updated_at" => [
            "type" => "string",
            "insert" => false,
            "query_condition" => false,
            "required_when_create" => false,
            "sort" => true
        ]
    ];
}

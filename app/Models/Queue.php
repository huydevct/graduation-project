<?php

namespace App\Models;

use App\Casts\Value;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ngocnm\LaravelHelpers\eloquent\BaseModel;


class Queue extends Model
{
    use HasFactory, BaseModel;

    protected $table = 'queues';

    protected $casts = [
        'value' => Value::class,
        'data' => Value::class,
    ];

    static $schema = [
        "id" => [
            "type" => "int",
            "insert" => false,
            "query_condition" => true,
            "sort" => true
        ],
        "type" => [
            "type" => "int",
            "insert" => false,
            "query_condition" => true,
            "sort" => true
        ],
        "data" => [
            "type" => "int",
            "insert" => false,
            "query_condition" => true,
            "sort" => true
        ],
        "process_time" => [
            "type" => "int",
            "insert" => false,
            "query_condition" => false,
            "sort" => false
        ],
        "status" => [
            "type" => "int",
            "insert" => false,
            "query_condition" => true,
            "sort" => false
        ],
        "value" => [
            "type" => "string",
            "insert" => false,
            "query_condition" => false,
            "sort" => false
        ],
        "error" => [
            "type" => "string",
            "insert" => false,
            "query_condition" => false,
            "sort" => false
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

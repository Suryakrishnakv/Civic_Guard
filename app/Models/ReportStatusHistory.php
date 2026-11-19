<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'status',
        'remarks',
        'changed_by',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

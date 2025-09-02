<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory, Tenantable;

    protected $fillable = [
        'company_id',
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_DONE = 'done';
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function getStatusPtBrAttribute(): string
    // {
    //     return match($this->status) {
    //         self::STATUS_PENDING => 'pendente',
    //         self::STATUS_IN_PROGRESS => 'em andamento',
    //         self::STATUS_DONE => 'concluída',
    //         default => 'desconhecido'
    //     };
    // }

    // public function getPriorityPtBrAttribute(): string
    // {
    //     return match($this->priority) {
    //         self::PRIORITY_LOW => 'baixa',
    //         self::PRIORITY_MEDIUM => 'média',
    //         self::PRIORITY_HIGH => 'alta',
    //         default => 'desconhecida'
    //     };
    // }
}

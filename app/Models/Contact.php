<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    use HasFactory;

    public function scopeSearch(Builder $query, array $filters): Builder
    {
        $keyword = $filters['keyword'] ?? null;
        $gender = $filters['gender'] ?? null;
        $categoryId = $filters['category_id'] ?? null;
        $date = $filters['date'] ?? null;

        return $query
            ->when($keyword, function (Builder $query, string $keyword) {
                // 名前は部分一致、メールアドレスは部分一致で検索する
                $query->where(function (Builder $query) use ($keyword) {
                    $query->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->when($gender, fn (Builder $query, $gender) => $query->where('gender', $gender))
            ->when($categoryId, fn (Builder $query, $categoryId) => $query->where('category_id', $categoryId))
            ->when($date, fn (Builder $query, $date) => $query->whereDate('created_at', $date));
    }

    protected $fillable = [
        'category_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'tel',
        'address',
        'building',
        'detail',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
}

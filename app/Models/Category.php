<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    /**
     * Fields that can be mass assigned
     */
    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Allowed types
     */
    public const TYPE_INCOME  = 'income';
    public const TYPE_EXPENSE = 'expense';

    /**
     * Scope for income transactions
     */
    public function scopeIncome($query)
    {
        return $query->where('type', self::TYPE_INCOME);
    }

    /**
     * Scope for expenses transactions
     */
    public function scopeExpense($query)
    {
        return $query->where('type', self::TYPE_EXPENSE);
    }

    /**
     * Accessor: checks if it is income
     */
    public function getIsIncomeAttribute()
    {
        return $this->type === self::TYPE_INCOME;
    }

    /**
     * Accessor: checks if it is an expense
     */
    public function getIsExpenseAttribute()
    {
        return $this->type === self::TYPE_EXPENSE;
    }

    /**
     * Mutator: ensures that the type is always valid
     */
    public function setTypeAttribute($value)
    {
        $allowed = [self::TYPE_INCOME, self::TYPE_EXPENSE];

        if (!in_array($value, $allowed)) {
            throw new \InvalidArgumentException("Invalid category type");
        }

        $this->attributes['type'] = $value;
    }

    /**
     * Get all transactions associated with this category.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    /**
     * Fields that can be mass assigned
     */
    protected $fillable = [
        'type',
        'amount',
        'description',
        'date',
        'category_id'
    ];

    /**
     * Automatic casts
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'date'   => 'date',
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
     * Accessor: returns formatted value
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, ',', '.');
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
            throw new \InvalidArgumentException("Invalid transaction type");
        }

        $this->attributes['type'] = $value;
    }

    /**
     * Helper: value with sign (+ or -)
     */
    public function getSignedAmountAttribute()
    {
        return $this->is_income
            ? $this->amount
            : -$this->amount;
    }

    /**
     * Get the category that this transaction belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope for filtering by type
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateBetween($query, $start, $end)
    {
        return $query
            ->when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end));
    }

    /**
     * Scope for description search
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('description', 'like', "%{$term}%");
    }

    /**
     * Scope for amount range
     */
    public function scopeAmountBetween($query, $min, $max)
    {
        return $query
            ->when($min, fn($q) => $q->where('amount', '>=', $min))
            ->when($max, fn($q) => $q->where('amount', '<=', $max));
    }
}
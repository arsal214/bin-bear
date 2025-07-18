<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyJobberToken extends Model
{
    use HasFactory;

    protected $table = 'company_jobber_tokens';
    protected $fillable = [
        'company_name',
        'company_email',
        'access_token',
        'refresh_token',
        'expires_at',
        'is_active'
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];
    
    /**
     * Get the active company token
     */
    public static function getActiveToken()
    {
        return self::where('is_active', true)->first();
    }
    
    /**
     * Set this token as the active one
     */
    public function setAsActive()
    {
        // Deactivate all other tokens
        self::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this token
        $this->update(['is_active' => true]);
        
        return $this;
    }
}

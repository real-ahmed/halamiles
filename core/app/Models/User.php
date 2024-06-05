<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable

{

    use HasApiTokens;



    /**

     * The attributes that should be hidden for arrays.

     *

     * @var array

     */

    protected $hidden = [

        'password', 'remember_token',

    ];



    /**

     * The attributes that should be cast to native types.

     *

     * @var array

     */

    protected $casts = [

        'email_verified_at' => 'datetime',

        'address' => 'object',

        'ver_code_send_at' => 'datetime'

    ];





    public function loginLogs()

    {

        return $this->hasMany(UserLogin::class);

    }



    public function deposits()

    {

        return $this->hasMany(Deposit::class)->where('status','!=',0);

    }

    public function withdraws()
    {
        return $this->hasManyThrough(Withdrawal::class, Transaction::class);

    }


    public function clicks()
    {
        return $this->hasMany(Click::class);

    }

    public function coupons()

    {

        return $this->hasMany(Coupon::class);

    }



    public function fullname(): Attribute

    {

        return new Attribute(

            get: fn () => $this->firstname . ' ' . $this->lastname,

        );

    }



    // SCOPES

    public function scopeActive()

    {

        return $this->where('status', 1);

    }



    public function scopeBanned()

    {

        return $this->where('status', 0);

    }



    public function scopeEmailUnverified()

    {

        return $this->where('ev', 0);

    }



    public function scopeMobileUnverified()

    {

        return $this->where('sv', 0);

    }



    public function scopeEmailVerified()

    {

        return $this->where('ev', 1);

    }



    public function scopeMobileVerified()

    {

        return $this->where('sv', 1);

    }



    public function scopeWithBalance()

    {

        return $this->where('balance','>', 0);

    }



    public function favorite(){

        return $this->hasMany(Favorite::class);

    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'model');
    }

    public function cashback(){

        return $this->hasMany(Cashback::class);

    }
    public function transactions ()
    {
        return $this->hasMany(Transaction::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function getBalanceAttribute()
    {
        return $this->calculateBalance(1,1);
    }

    public function BalanceWithdrawalMethod($cashoutMethodId){
        return $this->calculateBalance(1,1,$cashoutMethodId);
    }

    public function getPendingBalanceAttribute()
    {
        return $this->calculateBalance(1,0);
    }

    public function getCancelledBalanceAttribute()
    {
        return $this->calculateBalance(1,2);
    }

    public function getPointsAttribute()
    {
        return $this->calculateBalance(3,1);
    }

    public function getPendingPointsAttribute()
    {
        return $this->calculateBalance(3,0);
    }

    public function getCancelledPointsAttribute()
    {
        return $this->calculateBalance(3,2);
    }

    private function calculateBalance($cashbackTypeId,$status,$withdrawMethodId=null)
    {
        $transactions = $this->transactions->where('cashbacktype_id', $cashbackTypeId);

        if ($withdrawMethodId) {
            $transactions->where(function($query) use ($withdrawMethodId) {
                $query->whereHas('acceptedCashoutMethods', function($query) use ($withdrawMethodId) {
                    $query->where('withdraw_method_id', $withdrawMethodId);
                })->orWhere(function ($query) {
                    $query->doesntHave('acceptedCashoutMethods');
                });
            });
        }

        $balance = 0;

        foreach ($transactions as $transaction) {
            if (in_array($transaction->type, ['credit', 'gift', 'referrer', 'referral', 'cashback']) && $transaction->status == $status) {
                $balance += $transaction->amount;
            } elseif ($transaction->type == 'withdrawal') {
                if ($status == 1 &&  $transaction->status != 2){
                    $balance -= $transaction->amount;
                }

            }elseif ($transaction->type == 'banned withdrawal') {
                if ($status == 1 ){
                    $balance -= $transaction->amount;
                }
            }
        }

        return $balance;
    }



}


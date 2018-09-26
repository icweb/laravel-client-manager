<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Versionable\VersionableTrait;

class Client extends Model
{
    use SoftDeletes, VersionableTrait;

    protected $table = 'clients';

    protected $dates = [
        'deleted_at',
        'date_of_birth'
    ];

    protected $fillable = [
        'author_id',
        'gender',
        'chart_number',
        'legal_first_name',
        'legal_middle_name',
        'legal_last_name',
        'legal_suffix',
        'legal_prefix',
        'nickname',
        'birth_first_name',
        'birth_middle_name',
        'birth_last_name',
        'birth_suffix',
        'birth_prefix',
        'date_of_birth',
        'ssn',
        'comments',
    ];

    protected $appends = [
        'age',
        'alerts',
        'has_active_phone',
        'has_active_address',
        'has_active_email',
        'has_active_services',
        'expired_email_count',
        'expired_phone_count',
        'expired_address_count',
        'expired_services_count',
        'ssn_formatted'
    ];

    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_of_birth)->age;
    }

    public function getSsnFormattedAttribute()
    {
        return preg_replace("/^(\d{3})(\d{2})(\d{4})$/", "$1-$2-$3", $this->ssn);
    }

    public function name($type = 'legal')
    {
        return $type === 'legal' ?
            $this->legal_prefix . ' ' . $this->legal_first_name . ' ' . $this->legal_middle_name . ' ' . $this->legal_last_name . ' ' . $this->legal_suffix
            :
            $this->birth_prefix . ' ' . $this->birth_first_name . ' ' . $this->birth_middle_name . ' ' . $this->birth_last_name . ' ' . $this->birth_suffix
            ;
    }

    public function getAlertsAttribute()
    {
        $alerts = [];

        if($this->age < 18)
        {
            array_push($alerts, [
                'icon'      => 'child',
                'title'     => 'Minor (Under 18)',
                'message'   => $this->legal_first_name . ' will be 18 years old on ' . date('m/d/Y', strtotime($this->date_of_birth . ' + 18 years')),
                'fixIt'     => false
            ]);
        }

        if(!$this->has_active_phone)
        {
            array_push($alerts, [
                'icon'      => 'phone',
                'title'     => 'No Active Phone',
                'message'   => $this->legal_first_name . " doesn't have an active phone number",
                'fixIt'     => route('clients.show', [$this->id, 'phones']) . '#addPhone'
            ]);
        }
        else
        {
            $primary_phone = Phone::where(['client_id' => $this->id, 'primary' => 1])->get();

            if(count($primary_phone) && !$primary_phone[0]->expired)
            {
                if(strtotime($primary_phone[0]->expires_at) < strtotime('+ 30 days'))
                {
                    array_push($alerts, [
                        'icon'      => 'calendar',
                        'title'     => 'Phone Expires Soon',
                        'message'   => $this->legal_first_name . "'s primary phone number expires on " . date('m/d/Y', strtotime($primary_phone[0]->expires_at)),
                        'fixIt'     => route('clients.show', [$this->id, 'phones'])
                    ]);
                }
            }
            else
            {
                array_push($alerts, [
                    'icon'      => 'star',
                    'title'     => 'No Primary Phone',
                    'message'   => $this->legal_first_name . " doesn't have a primary phone number",
                    'fixIt'     => route('clients.show', [$this->id, 'phones'])
                ]);
            }
        }

        if(!$this->has_active_address)
        {
            array_push($alerts, [
                'icon'      => 'home',
                'title'     => 'No Active Address',
                'message'   => $this->legal_first_name . " doesn't have an active address",
                'fixIt'     => route('clients.show', [$this->id, 'addresses']) . '#addAddress'
            ]);
        }
        else
        {
            $primary_address = Address::where(['client_id' => $this->id, 'primary' => 1])->get();

            if(count($primary_address) && !$primary_address[0]->expired)
            {
                if(strtotime($primary_address[0]->expires_at) < strtotime('+ 30 days'))
                {
                    array_push($alerts, [
                        'icon'      => 'calendar',
                        'title'     => 'Address Expires Soon',
                        'message'   => $this->legal_first_name . "'s primary address expires on " . date('m/d/Y', strtotime($primary_address[0]->expires_at)),
                        'fixIt'     => route('clients.show', [$this->id, 'addresses'])
                    ]);
                }
            }
            else
            {
                array_push($alerts, [
                    'icon'      => 'star',
                    'title'     => 'No Primary Address',
                    'message'   => $this->legal_first_name . " doesn't have a primary address",
                    'fixIt'     => route('clients.show', [$this->id, 'addresses'])
                ]);
            }
        }

        if(!$this->has_active_email)
        {
            array_push($alerts, [
                'icon'      => 'envelope',
                'title'     => 'No Active Email',
                'message'   => $this->legal_first_name . " doesn't have an active email address",
                'fixIt'     => route('clients.show', [$this->id, 'emails']) . '#addEmail'
            ]);
        }

        if(!$this->has_active_services)
        {
            array_push($alerts, [
                'icon'      => 'list',
                'title'     => 'No Active Services',
                'message'   => $this->legal_first_name . " doesn't have any active services",
                'fixIt'     => route('clients.show', [$this->id, 'services']) . '#addService'
            ]);
        }

        return $alerts;
    }

    public function getHasActivePhoneAttribute()
    {
        return Phone::where(['client_id' => $this->id])
            ->where('active_at', '<', date('Y-m-d H:i:s', time()))
            ->where('expires_at', '>', date('Y-m-d H:i:s', time()))
            ->whereNotNull('expires_at')
            ->whereNotNull('active_at')
            ->get()
            ->count();
    }

    public function getHasActiveEmailAttribute()
    {
        return Email::where(['client_id' => $this->id])
            ->where('active_at', '<', date('Y-m-d H:i:s', time()))
            ->where('expires_at', '>', date('Y-m-d H:i:s', time()))
            ->whereNotNull('expires_at')
            ->whereNotNull('active_at')
            ->get()
            ->count();
    }

    public function getHasActiveAddressAttribute()
    {
        return Address::where(['client_id' => $this->id])
            ->where('active_at', '<', date('Y-m-d H:i:s', time()))
            ->where('expires_at', '>', date('Y-m-d H:i:s', time()))
            ->whereNotNull('expires_at')
            ->whereNotNull('active_at')
            ->get()
            ->count();
    }

    public function getHasActiveServicesAttribute()
    {
        return Service::where(['client_id' => $this->id])
            ->where('active_at', '<', date('Y-m-d H:i:s', time()))
            ->where('expires_at', '>', date('Y-m-d H:i:s', time()))
            ->whereNotNull('expires_at')
            ->whereNotNull('active_at')
            ->get()
            ->count();
    }

    public function getExpiredPhoneCountAttribute()
    {
        return Phone::where(['client_id' => $this->id])
            ->where('expires_at', '<', date('Y-m-d H:i:s', time()))
            ->whereNotNull('expires_at')
            ->get()
            ->count();
    }

    public function getExpiredEmailCountAttribute()
    {
        return Email::where(['client_id' => $this->id])
            ->where('expires_at', '<', date('Y-m-d H:i:s', time()))
            ->whereNotNull('expires_at')
            ->get()
            ->count();
    }

    public function getExpiredAddressCountAttribute()
    {
        return Address::where(['client_id' => $this->id])
            ->where('expires_at', '<', date('Y-m-d H:i:s', time()))
            ->whereNotNull('expires_at')
            ->get()
            ->count();
    }

    public function getExpiredServicesCountAttribute()
    {
        return Service::where(['client_id' => $this->id])
            ->where('expires_at', '<', date('Y-m-d H:i:s', time()))
            ->whereNotNull('expires_at')
            ->get()
            ->count();
    }

    public static function getNextChartNumber()
    {
        return Client::select('chart_number')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->get()
            ->pluck('chart_number')
            ->toArray()[0] + 1;
    }

    public function assign(User $user, $type)
    {
        $assignment = ClientAssignment::create([
            'author_id' => auth()->user()->id,
            'user_id'   => $user->id,
            'client_id' => $this->id,
            'type'      => $type
        ]);
    }

    public function scopeMine($query)
    {
        return $query->whereIn('id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class)->orderBy('primary', 'desc');
    }

    public function phones()
    {
        return $this->hasMany(Phone::class)->orderBy('primary', 'desc');
    }

    public function emails()
    {
        return $this->hasMany(Email::class)->orderBy('primary', 'desc');
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function caseNotes()
    {
        return $this->hasMany(CaseNote::class);
    }

    public function recordView()
    {
        return $this->hasMany(ClientRecordView::class, 'client_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\LookupInvitation;
use App\Models\Traits\Inviteable;

/**
 * Class Invitation.
 */
class Invitation extends EntityModel
{
    use SoftDeletes;
    use Inviteable;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return mixed
     */
    public function getEntityType()
    {
        return ENTITY_INVITATION;
    }

    /**
     * @return mixed
     */
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function contact()
    {
        return $this->belongsTo('App\Models\Contact')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }
}

Invitation::creating(function ($invitation)
{
    LookupInvitation::createNew($invitation->account->account_key, [
        'invitation_key' => $invitation->invitation_key,
    ]);
});

Invitation::updating(function ($invitation) {
    $dirty = $invitation->getDirty();
    if (array_key_exists('message_id', $dirty)) {
        LookupInvitation::updateInvitation($invitation->account->account_key, $invitation);
    }
});

Invitation::deleted(function ($invitation)
{
    if ($invitation->forceDeleting) {
        LookupInvitation::deleteWhere([
            'invitation_key' => $invitation->invitation_key,
        ]);
    }
});

<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * Class User
 */
class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    protected $fillable = array('cs50id', 'cs50fullname', 'cs50email', 'first_name', 'last_name');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'cs50id');

    public function toArray() {
        $array = parent::toArray();
        $tweeter = Tweeter::find($this->tweeter_id);
        $array['gravatar'] = $this->getGravatarHash();
        if($tweeter) {
           $array['handle'] = $tweeter->handle;
         }
        return $array;
    }

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}


    /**
     * Create gravatar url from the users email
     * Use the private email if available, otherwise the
     * cs50id email
     * @return string
     */
    private function getGravatarHash()
    {
        if ($this->email) {
            $email = $this->email;
        } else {
            $email = $this->cs50email;
        }
        $email = trim($email);
        $email = strtolower($email);
        return md5($email);
    }

    /**
     * Set a twitter handle for the user.
     * Will either create or override existing handle
     * @param $handle
     */
    public function setTwitterHandle($handle) {

        if ($this->tweeter_id) {
            // Update existing tweeter
            $tweeter = Tweeter::find($this->tweeter_id);
            $tweeter->handle = $handle;
            $tweeter->save();
        }
        else {
            // Create new tweeter
            $tweeter = new Tweeter;
            $tweeter->name = $this->last_name ? $this->first_name . ' ' . $this->last_name : $this->cs50fullname;
            $tweeter->handle = $handle;
            $tweeter->save();
            $this->tweeter_id = $tweeter->id;
        }
        // For consistency, since tweeter already saved
        $this->save();
    }

    /**
     * Return a validator for the user model
     * Used for signup
     * @param $input
     * @return Validator
     */
    public static function validate($input) {

        $rules = array(
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required'
        );

        return Validator::make($input, $rules);
    }

    public static function updateValidator($input, $user) {

        $rules = array(
            // allow user to keep their current email
            'email' => 'required|email|unique:users,email,' . $user->id ,
            'first_name' => 'required',
            'last_name' => 'required'
        );

        return Validator::make($input, $rules);
    }

}

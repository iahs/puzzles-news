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

    protected $fillable = array('cs50id', 'cs50fullname', 'cs50email');

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
     * Return a validator for the user model
     *
     * @param $input
     * @return Validator
     */
    public static function validate($input) {

        $rules = array(
            'email' => 'required|email|unique:users',
            'password' => 'required'
        );

        return Validator::make($input, $rules);

    }

}

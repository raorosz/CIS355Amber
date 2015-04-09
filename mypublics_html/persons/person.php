<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 3/26/15
 * Time: 11:02 PM
 */

class Person {
    private $id;
    private $role;
    private $first_name;
    private $last_name;
    private $email;
    private $password_hash;
    private $school;

    /**
     * Person constructor.
     * @param $id
     * @param $role
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param $password_hash
     * @param $school
     */
    public function __construct($role, $first_name, $last_name, $email, $school, $password_hash = "", $id = 0) {
        $this->id = $id;
        $this->role = $role;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->school = $school;
    }

    public function __get($name) {
        return $this->$name;
    }

}
<?php

/**
 * Class Administration
 *
 * Only admins can see this site. It shows all the users in the system.
 */
class Administration extends Controller
{
    public function __construct()
    {
        $this->view = "administration";
        $this->metadata['title'] = "Administration - GeCon";
    }

    /**
     * Method for filling table with users respective to the given role.
     *
     * @param $role
     */
    public function tableOfUsers($role)
    {
        $local_path = "horacekv_web/";
        foreach ($_SESSION['users'] as $u)
        {
            if ($u['ROLE'] == $role)
            {
                echo "
                <tr>
                    <th scope=\"row\">
                        <button onclick=\"window.location=' " . $local_path . "index.php?page=account&manager=". $u['LOGIN'] ."'\" 
                            class=\"btn btn-dark col-12\" type=\"button\">
                            " . $u['LOGIN'] . "
                        </button>
                    </th>
                    <td>" . $u['EMAIL'] . "</td>
                    <td>" . $u['FULL_NAME'] . "</td>
                </tr>";
            }
        }
    }

    /**
     * Main method for each controller.
     *
     * @param $database
     * @return mixed
     */
    public function work($database)
    {
        $users = $database->allUsersInfo();
        $_SESSION['users'] = $users;
    }

    /**
     * Method for displaying content of this site.
     *
     * @return mixed
     */
    public function display()
    {
        if ($this->view)
        {
            extract($this->metadata, EXTR_PREFIX_ALL, "");
            require ("views/structure.phtml");
        }
    }
}
?>
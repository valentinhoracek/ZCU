<?php

class Administration extends Controller
{
    public function __construct ()
    {
        $this->view = "administration";
        $this->metadata['title'] = "Administration - GeCon";
    }

    public function work ($database)
    {
        //$_SESSION['user'] = "";
        $users = $database->allUsersInfo();
        $_SESSION['users'] = $users;
    }

    public function tableOfUsers($role)
    {
        $local_path = "horacekv_web/";
        $i = 0;
        foreach ($_SESSION['users'] as $u)
        {
            $i++;
            if ($u['ROLE'] == $role)
            {
                //<a href=\"" . $local_path . "index.php?page=account\">" . $i . "</a>
                echo "
                <tr>
                    <th scope=\"row\">
                        <button onclick=\"window.location=' " . $local_path . "index.php?page=account&manager=". $u['LOGIN'] ."'\" 
                            class=\"btn btn-dark col-lg-2 col-md-3 col-sm-6\" type=\"button\">
                            " . $u['LOGIN'] . "
                        </button>
                    </th>
                    <td>" . $u['EMAIL'] . "</td>
                    <td>" . $u['FULL_NAME'] . "</td>
                </tr>";
            }
        }
    }


    public function display ()
    {
        if ($this->view)
        {
            extract($this->metadata, EXTR_PREFIX_ALL, "");
            require ("views/structure.phtml");
        }
    }
}

?>
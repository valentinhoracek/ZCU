<?php

/**
 * Class Account
 *
 * You can change your own account information.
 * Admins can manage other user's information or delete users.
 */
class Account extends Controller
{
    public function __construct()
    {
        $this->view = "account";
        $this->metadata['title'] = "Account - GeCon";
    }

    /**
     * Method for updating user's information.
     *
     * @param $database
     */
    public function updateUser($database)
    {
        /**
         * Check if inputs are filled.
         */
        if (!$_POST['fullName'])
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill your name!</div>";
        }
        elseif (!$_POST['email'])
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill your E-mail address!</div>";
        }
        elseif (!$_POST['login'])
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill your login!</div>";
        }
        elseif (!$database->isPasswordCorrect($_SESSION['user']['LOGIN'], $_POST['oldPassword']))
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Your current password is incorrect!</div>";
        }
        elseif ($_POST['password'] != $_POST['passwordCheck'])
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    New passwords don't match!</div>";
        }
        else
        {
            /**
             * If new password isn't set
             */
            if ($_POST['password'] == "")
            {
                $password = $_SESSION['user']['PASSWORD'];
            }
            else
            {
                $password = md5($_POST['password']);
            }

            /**
             * Update information in database.
             */
            $database->updateUserInfo(
                $_SESSION['user']['ID_USER'],
                $_POST['fullName'],
                $_POST['email'],
                $_POST['login'],
                $password,
                $_SESSION['user']['ROLE']
            );

            /**
             * Login user.
             */
            $database->userLogin($_SESSION['user']['LOGIN'], $password);
            echo "<div class=\"alert alert-light\" role=\"alert\">
                            Account info changed!</div>";
        }
    }

    /**
     * Method for deleting user.
     *
     * @param $database
     */
    public function deleteUser($database)
    {
        /**
         * Delete reviews for the user.
         */
        $result = $database->deleteReviewsForUser($_SESSION['managedUser']['ID_USER']);

        if (!$result)
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Failed to delete user's reviews!</div>";
        }
        else
        {
            echo "<div class=\"alert alert-light\" role=\"alert\">
                    User's reviews deleted!</div>";
        }

        /**
         * Delete articles for the user
         */
        $result = $database->deleteArticlesForUser($_SESSION['managedUser']['ID_USER']);

        if (!$result)
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Failed to delete user's articles!</div>";
        }
        else
        {
            echo "<div class=\"alert alert-light\" role=\"alert\">
                    User's articles deleted!</div>";
        }

        /**
         * Delete user entry.
         */
        $result = $database->deleteUser($_SESSION['managedUser']['ID_USER']);

        if (!$result)
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Failed to delete user information!</div>";
        }
        else
        {
            echo "<div class=\"alert alert-light\" role=\"alert\">
                    Account of user: ". $_SESSION['managedUser']['LOGIN'] ." deleted!</div>";
        }
    }

    /**
     * Method for managing other user's information.
     *
     * @param $database
     */
    public function manageUser($database)
    {
        /**
         * Check if inputs are filled.
         */
        if (!$_POST['fullName'])
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill user name!</div>";
        }
        elseif (!$_POST['email'])
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill user E-mail address!</div>";
        }
        elseif (!$_POST['login'])
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill user login!</div>";
        }
        else
        {
            /**
             * Update information in database.
             */
            $result = $database->updateUserInfo(
                $_SESSION['managedUser']['ID_USER'],
                $_POST['fullName'],
                $_POST['email'],
                $_POST['login'],
                $_SESSION['managedUser']['PASSWORD'],
                $_POST['role']
            );

            if (!$result)
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Failed to change info!</div>";
            }
            else
            {
                echo "<div class=\"alert alert-light\" role=\"alert\">
                    Account info for user: ". $_SESSION['managedUser']['LOGIN'] ." changed!</div>";
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
        /**
         * Managing another users info.
         */
        if (isset($_GET['manager']))
        {
            $_SESSION['managedUser'] = $database->allUserInfo($_GET['manager']);
        }
        else
        {
            $_SESSION['managedUser'] = $_SESSION['user'];
        }

        /**
         * Calling function respective to submitted button.
         */
        if (isset($_POST['update']))
        {
            $this->updateUser($database);
        }
        elseif (isset($_POST['delete']))
        {
            $this->deleteUser($database);
        }
        elseif (isset($_POST['manage']))
        {
            $this->manageUser($database);
        }
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
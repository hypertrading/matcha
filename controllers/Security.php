<?php
class Security extends VK_Controller{
    function connexion(){
        $this->views('security/connexion');
    }
    function register(){
        $this->views('security/register');
    }
    function lost_password(){
        $this->views('security/lost_password');
    }

    function new_user(){
        $inputs = array(
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'email' => $_POST['email'],
            'date_naissance' => $_POST['date_naissance'],
            'password' => $_POST['password'],
            'sexe' => $_POST['sexe']
            );
        if(preg_match("/[A-Za-z _àèéùç-]/", $inputs['nom']) != 1 )
        {
            $this->set(array('info' => 'Le champ nom n\'est pas conforme.'));
            $this->views('security/register');
        }
        else if(preg_match("/[A-Za-z _àèéùç-]/", $inputs['prenom'] != 1))
        {
            $this->set(array('info' => 'Le champ prénom n\'est pas conforme.'));
            $this->views('security/register');
        }
        else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE)
        {
            $this->set(array('info' => 'Le champ email n\'est pas conforme.'));
            $this->views('security/register');
        }
        else if(preg_match("/^[0-9](2)\/[0-9](2)\/[1-2](1)[0-9](3)$/", $inputs['date_naissance']) !== 1)
        {
            $this->set(array('info' => 'Le champ date de naissance n\'est pas conforme.'));
            $this->views('security/register');
        }
        else if(preg_match("/[a-ZA-Z0-9\!\?\,\;\.\&\"\'-_@)\]\[\{\}\(]/", $inputs['password']) !== 1)
        {
            $this->set(array('info' => 'Le champ mot de passe n\'est pas conforme.'));
            $this->views('security/register');
        }
        else if($inputs['sexe'] != 1 || $inputs['sexe'] != 2)
        {
            $this->set(array('info' => 'Veullez nous dire si vous etes un homme ou une femme. Ca nous aidera ;)'));
            $this->views('security/register');
        }
        else if($this->user_model->insert_user($inputs) == TRUE)
        {
            $this->views('security/connexion');
        }
        else
            $this->views('security/register');
    }
}
?>
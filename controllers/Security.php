<?php
class Security extends VK_Controller {
    function connexion(){
        $this->views('security/connexion');
    }
    function register(){
        $this->views('security/register');
    }
    function lost_password(){
        $this->views('security/lost_password');
    }
    function authentification(){
        if (preg_match("/[A-Za-z _àèéùç-]/", $_POST['pseudo']) != 1) {
            $this->set (array ('info' => 'Le champ pseudo n\'est pas conforme.'));
            $this->views('security/connexion');
        }
        else if(preg_match("/[a-zA-Z0-9!?,;.&\"'-_@)\][{}\(]/", $_POST['password']) != 1){
            $this->set (array ('info' => 'Le champ mot de passe n\'est pas conforme.'));
            $this->views('security/connexion');
        }
        else
        {
            if ($user = $this->user_model->get_one_user($_POST['pseudo']))
            {
                $passwd = hash("whirlpool", $_POST['password']);
                if ($passwd == $user['password'] && $user['status'] == 1)
                {
                    $_SESSION['user'] = $user;
                    unset($_SESSION['user']['password']);
                    if ($user['droits'] == 1)
                        $_SESSION['admin'] = 1;
                    $this->user_model->update_last_login($user['id']);
                    header('Location: ../welcome/index');
                }
                else {
                    $this->set(array('info' => 'Pas de correspondance email/mot de passe.'));
                    $this->views('security/connexion');
                }
            }
            else {
                $this->set(array('info' => 'Pas de correspondance email/mot de passe.'));
                $this->views('security/connexion');
            }
        }
    }
    function new_user(){
        $inputs = array(
            'pseudo' => $_POST['pseudo'],
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'email' => $_POST['email'],
            'date_naissance' => $_POST['date_naissance'],
            'password' => $_POST['password'],
            'sexe' => $_POST['sexe']);

        if (preg_match("/[A-Za-z _àèéùç-]/", $inputs['pseudo']) != 1 ) {
            $this->set(array('info' => 'Le champ speudo n\'est pas conforme.'));
            $this->views('security/register');
        }
        else if (preg_match("/[A-Za-z _àèéùç-]/", $inputs['nom']) != 1 ) {
            $this->set(array('info' => 'Le champ nom n\'est pas conforme.'));
            $this->views('security/register');
        }
        else if (preg_match("/[A-Za-z _àèéùç-]/", $inputs['prenom']) != 1) {
            $this->set(array('info' => 'Le champ prénom n\'est pas conforme.'));
            $this->views('security/register');
        }
        else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE) {
            $this->set(array('info' => 'Le champ email n\'est pas conforme.'));
            $this->views('security/register');
        }
        else if (preg_match("/^(19|20)([0-9](2))[- \/.](0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])$/", $inputs['date_naissance']) !== 1) {
            $this->set(array('info' => 'Le champ date de naissance n\'est pas conforme.'));
            $this->views('security/register');
        }
        else if (preg_match("/[a-zA-Z0-9!?,;.&\"'-_@)\][{}\(]/", $inputs['password']) != 1) {
            $this->set(array('info' => 'Le champ mot de passe n\'est pas conforme.'));
            $this->views('security/register');
        }
        else if ($_POST['val_password'] != $inputs['password']) {
            $this->set(array('info' => 'Les mots de passes ne sont pas identiques !'));
            $this->views('security/register');
        }
        else if ($inputs['sexe'] != 1 && $inputs['sexe'] != 2) {
            $this->set(array('info' => 'Veullez nous dire si vous etes un homme ou une femme. Ca nous aidera ;)'));
            $this->views('security/register');
        }
        else if($this->user_model->get_one_user($inputs['email']) == FALSE) {
            if($this->user_model->insert_user($inputs) == TRUE) {
                $user = $this->user_model->get_one_user($inputs['pseudo']);
                $id = (int)$user['id'];
                $entropy = mt_rand();
                $binary_token = pack('IS', $id, $entropy);
                $token = rtrim(strtr(base64_encode($binary_token), '+/', '-_'), '=');
                $link = $this->base_url()."security/valid_acount?t=".$token;
                $this->security_model->insert_token($token);
                $to = $_POST['email'];
                $subject = "Finisez votre inscription sur Matcha";
                $message = '<html>
                            <head>
                                <title>Validation de votre inscription</title>
                            </head>
                            <body>
                                <h3>Bonjour, vous venez de vous inscrire sur Matcha. Vous verrez c\'est super.</h3>
                                <p>Utilisez le lien suivant pour valider votre compte <a href="'.$link.'">Lien suivant</a></p>
                            </body>
                            </html>';
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=UTF8' . "\r\n";
                mail($to, $subject, $message, $headers);
                $this->set( array( 'info' => '<a href="'.$link.'">test</a>'));
                $this->views('security/connexion');
            }
            else {
                $this->views('security/register');
            }
        }
        else {
            $this->set(array('info' => 'Cet email est déjà relié à un autre compte.'));
            $this->views('security/register');
        }
    }
    function valid_acount($token)
    {
        if (!$token) {
            $this->set(array('info' => 'Le lien n\'est pas valide'));
            $this->views('security/connexion');
            exit;
        }
        $binary_token = base64_decode(str_pad(strtr($token, '-_', '+/'), strlen($token) % 4, '=', STR_PAD_RIGHT));
        if (!$binary_token) {
            $this->set(array('info' => 'Le token n\'est pas valide 1'));
            $this->views('security/connexion');
            exit;
        }
        $data = @unpack('Iid/Sentropy', $binary_token);
        if (!$data) {
            $this->set(array('info' => 'Le token n\'est pas valide 2'));
            $this->views('security/connexion');
            exit;
        }
        if($this->security_model->token_match($token))
        {
            if($this->security_model->valid_acount($data['id']))
            {
                $this->security_model->rm_token($token);
                $this->set(array('info' => 'Votre compte à bien été validé ! Connectez vous pour continuer.'));
                $this->views('security/connexion');
                exit;
            }
        }
    }
    function logout(){
        unset($_SESSION['user']);
        $this->set(array('info' => 'A bientot !'));
        header('Location: ../welcome/index');
    }

}
?>
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
                if ($passwd == $user['password'] && $user['status'] != 0)
                {
                    $_SESSION['user'] = $user;
                    unset($_SESSION['user']['password']);
                    if ($user['droits'] == 1)
                        $_SESSION['admin'] = 1;
                    $this->user_model->update_last_login($user['id']);
                    header('Location: ../match/decouverte');
                }
                else {
                    $this->set(array('info' => 'Pas de correspondance email/mot de passe.'));
                    header('Location: '.$this->base_url().'security/connexion');
                }
            }
            else {
                $this->set(array('info' => 'Pas de correspondance email/mot de passe.'));
                header('Location: '.$this->base_url().'security/connexion');
            }
        }
    }
    function new_user(){
        $pos = $this->geoloc->get_coord();
        $inputs = array(
            'pseudo' => $_POST['pseudo'],
            'nom' => ucfirst(strtolower($_POST['nom'])),
            'prenom' => ucfirst(strtolower($_POST['prenom'])),
            'email' => $_POST['email'],
            'date_naissance' => $_POST['date_naissance'],
            'password' => $_POST['password'],
            'sexe' => $_POST['sexe'],
            'localisation' => $this->geoloc->get_place_id(),
            'lat' => $pos['lat'],
            'lng' => $pos['lng']);

        if (preg_match("/[A-Za-z _àèéùç-]/", $inputs['pseudo']) != 1) {
            $this->set(array('info' => 'Le champ pseudo n\'est pas conforme.'));
            header('Location: '.$this->base_url().'security/register');
        }
        else if ($this->user_model->value_unique('pseudo', $inputs['pseudo'], -1) == FALSE) {
            $this->set(array('info' => 'Ce pseudo est deja pris.'));
            header('Location: '.$this->base_url().'security/register');
        }
        else if (preg_match("/[A-Za-z _àèéùç-]/", $inputs['nom']) != 1 ) {
            $this->set(array('info' => 'Le champ nom n\'est pas conforme.'));
            header('Location: '.$this->base_url().'security/register');
        }
        else if (preg_match("/[A-Za-z _àèéùç-]/", $inputs['prenom']) != 1) {
            $this->set(array('info' => 'Le champ prénom n\'est pas conforme.'));
            header('Location: '.$this->base_url().'security/register');
        }
        else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE) {
            $this->set(array('info' => 'Le champ email n\'est pas conforme.'));
            header('Location: '.$this->base_url().'security/register');
        }
        else if (!$this->valid_date($inputs['date_naissance'])) {
            $this->set(array('info' => 'Le champ date de naissance n\'est pas conforme.'));
            header('Location: '.$this->base_url().'security/register');
        }
        else if (preg_match("/[a-zA-Z0-9!?,;.&\"'-_@)\][{}\(]/", $inputs['password']) != 1) {
            $this->set(array('info' => 'Le champ mot de passe n\'est pas conforme.'));
            header('Location: '.$this->base_url().'security/register');
        }
        else if ($_POST['val_password'] != $inputs['password']) {
            $this->set(array('info' => 'Les mots de passes ne sont pas identiques !'));
            header('Location: '.$this->base_url().'security/register');
        }
        else if ($inputs['sexe'] != 1 && $inputs['sexe'] != 2) {
            $this->set(array('info' => 'Veullez nous dire si vous etes un homme ou une femme. Ca nous aidera ;)'));
            header('Location: '.$this->base_url().'security/register');
        }
        else if($this->user_model->value_unique('email', $inputs['email'], -1) == TRUE) {
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
                $this->set( array( 'info' => 'Vous allez recevoir un email afin de valider votre inscription.'));
                header('Location: '.$this->base_url().'security/connexion');
            }
            else {
                $this->set( array( 'info' => 'Une erreur c\'est produite. Veuillez retentez ulterieurement.'));
                header('Location: '.$this->base_url().'security/register');
            }
        }
        else {
            $this->set(array('info' => 'Cet email est déjà relié à un autre compte.'));
            header('Location: '.$this->base_url().'security/register');
        }
    }
    function valid_acount($token) {
        if (!$token) {
            $this->set(array('info' => 'Le lien n\'est pas valide'));
            header('Location: '.$this->base_url());
            exit;
        }
        $binary_token = base64_decode(str_pad(strtr($token, '-_', '+/'), strlen($token) % 4, '=', STR_PAD_RIGHT));
        if (!$binary_token) {
            $this->set(array('info' => 'Le token n\'est pas valide'));
            header('Location: '.$this->base_url());
            exit;
        }
        $data = @unpack('Iid/Sentropy', $binary_token);
        if (!$data) {
            $this->set(array('info' => 'Le token n\'est pas valide'));
            header('Location: '.$this->base_url());
            exit;
        }
        if ($this->security_model->token_match($token)) {
            if($this->security_model->valid_acount($data['id']))
            {
                $this->security_model->rm_token($token);
                $this->set(array('info' => 'Votre compte à bien été validé ! Connectez vous pour continuer.'));
                header('Location: '.$this->base_url().'security/connexion');
                exit;
            }
        }
    }
    function logout(){
        $this->user_model->set_offline($_SESSION['user']['id']);
        unset($_SESSION['user']);
        $this->set(array('info' => 'A bientôt !'));
        header('Location: ../welcome/index');
        exit;
    }
    function send_password(){
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE){
            
        }
        else if (preg_match("/[A-Za-z _àèéùç-]/", $_POST['prenom']) != 1){
            
        }
        else if(preg_match("/[A-Za-z _àèéùç-]/", $_POST['nom']) != 1){
            
        }
        else {
            $info = $this->user_model->get_info_min($_POST['email']);
            if(!isset($info)){
                echo 'pas d user';
            }
            else if($info['prenom'] != ucfirst($_POST['prenom']) || $info['nom'] != ucfirst($_POST['nom'])){
                echo 'info non concordante';
            }
            else {
                $id = (int)$info['id'];
                $entropy = mt_rand();
                $binary_token = pack('IS', $id, $entropy);
                $token = rtrim(strtr(base64_encode($binary_token), '+/', '-_'), '=');
                $link = $this->base_url()."security/edit_password?t=".$token;
                $this->security_model->insert_token($token);

                $to = $_POST['email'];
                $subject = "Reinitialisation de votre mot de passe";
                $message = '<html>
                            <head>
                                <title>Reinitialisation de votre mot de passe</title>
                            </head>
                            <body>
                                <h2>Bonjour, vous venez de demander la reinitialisation de votre mot de passe.</h2>
                                <p>Utilisez le lien suivant pour acceder au formulaire : <a href="'.$link.'">Matcha</a></p>
                                <p>A bientot sur matcha</p>
                            </body>
                            </html>';
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=UTF8' . "\r\n";
                mail($to, $subject, $message, $headers);
                $this->set( array( 'info' => 'Vous allez recevoir un email afin de valider votre inscription.'));
                header('Location: '.$this->base_url().'security/connexion');
            }
        }
    }
    function edit_password($token){
        if (!$token) {
            $this->set(array('info' => 'Le lien n\'est pas valide'));
            header('Location: '.$this->base_url());
            exit;
        }
        $binary_token = base64_decode(str_pad(strtr($token, '-_', '+/'), strlen($token) % 4, '=', STR_PAD_RIGHT));
        if (!$binary_token) {
            $this->set(array('info' => 'Le token n\'est pas valide'));
            header('Location: '.$this->base_url());
            exit;
        }
        $data = @unpack('Iid/Sentropy', $binary_token);
        if (!$data) {
            $this->set(array('info' => 'Le token n\'est pas valide'));
            header('Location: '.$this->base_url());
            exit;
        }
        if ($this->security_model->token_match($token)) {
            $_SESSION['id_tmp'] = $data['id'];
            $_SESSION['token_tmp'] = $token;
            $this->views('security/new_password');
        }
        else {
            $this->set(array('info' => 'Le token n\'existe plus.'));
            header('Location: '.$this->base_url());
            exit;
        }
    }
    function update_password(){
        $this->array_debug($_SESSION);
        if(!$_SESSION['id_tmp'] || !$_SESSION['token_tmp']){
            $this->set(array('info' => 'Error'));
            header('Location: '.$this->base_url());
        }
        else if (preg_match("/[a-zA-Z0-9!?,;.&\"'-_@)\][{}\(]/", $_POST['password']) != 1) {
            $this->set(array('info' => 'Le champ mot de passe n\'est pas conforme.'));
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        else if ($_POST['val_password'] != $_POST['password']) {
            $this->set(array('info' => 'Les mots de passes ne sont pas identiques !'));
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        else {
            $this->user_model->new_password(hash('whirlpool',$_POST['password']), $_SESSION['id_tmp']);
            $this->security_model->rm_token($_SESSION['token_tmp']);
            unset($_SESSION['id_tmp']);
            unset($_SESSION['token_tmp']);
            $this->set(array('info' => 'Impec ! Votre mot de passe a ete mise a jour.'));
            header('Location: '.$this->base_url().'security/connexion');
        }
    }
}
?>
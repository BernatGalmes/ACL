<?php
/**
 * @deprecated Not implemented in current version
 */
namespace General;

use App\Seguritat;
use PhpGene\Input;
use PhpGene\Messages;
use PhpGene\Token;
use PHPACL\User;
use PHPACL\Users;

require_once __DIR__ . '/../init.php';
//require_once PATH_CORE . '/App/Mail_settings.php';


global $app;
if (!Seguritat::Page($app, $_SERVER['PHP_SELF'])) {
    Redirect::forbiddenPage($_SERVER['PHP_SELF']);
}

$msgs = new Messages();
$validation = new Validate();

//Temporary Success Message
$holdover = Input::get('success');
if ($holdover == 'true') {
    echo("<BR>Account Updated</BR>");
}

//Forms posted
if (!empty($_POST)) {
    $token = $_POST['csrf'];
    if (!Token::check($token)) {
        die('Token doesn\'t match!');
    } else {
        $user = Users::pageLogin($app);

    }
}

if (!isset($user)){
    $user = new User($app->getUser()->getData());
}

$msgs->merge($validation->getMissatges());


require_once PATH_INCLUDES . '/header.php';
?>
    <div id="page-wrapper">
        <div class="container">
            <div class="well">
                <div class="row">
                    <div class="col-xs-12 col-md-2"></div>
                    <div class="col-xs-12 col-md-10">
                        <h1>Actualiza tus datos personales</h1>
                        <section class="row">
                            <?= $msgs->html() ?>
                        </section>

                        <form name='updateAccount' action='user_settings.php' method='post'>

                            <div class="form-group">
                                <label>Nombre de usuario</label>
                                <input class='form-control' type='text' name='username' value='<?= $user->getAttr('username') ?>'
                                       readonly/>
                            </div>

                            <div class="form-group">
                                <label>Nombre</label>
                                <input class='form-control' type='text' name='fname' value='<?= $user->getAttr('fname') ?>'/>
                            </div>

                            <div class="form-group">
                                <label>Apellido/s</label>
                                <input class='form-control' type='text' name='lname' value='<?= $user->getAttr('lname') ?>'/>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input class='form-control' type='text' name='email' value='<?= $user->getAttr('email') ?>'/>
                            </div>

                            <div class="form-group">
                                <label>password antiguo (requerido para cambiar el password)</label>
                                <input class='form-control' type='password' name='old'/>
                            </div>

                            <div class="form-group">
                                <label>Nuevo Password (mínimo 8 carácteres)</label>
                                <input class='form-control' type='password' name='password'/>
                            </div>

                            <div class="form-group">
                                <label>Confirmar Password</label>
                                <input class='form-control' type='password' name='confirm'/>
                            </div>

                            <input type="hidden" name="csrf" value="<?= Token::generate(); ?>"/>

                            <p><input class='btn btn-primary' type='submit' value='Update'/></p>
                            <p><a class="btn btn-warning" href="account.php">Volver</a></p>

                        </form>
                    </div>
                </div>
            </div>


        </div> <!-- /container -->

    </div> <!-- /#page-wrapper -->


    <!-- footers -->
<?php require_once PATH_INCLUDES . '/page_footer.php'; // the final html footer copyright row + the external js calls
?>

    <!-- Place any per-page javascript here -->

<?php require_once PATH_INCLUDES . '/html_footer.php'; // currently just the closing /body and /html
?>
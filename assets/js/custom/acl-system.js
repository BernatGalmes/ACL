/**
 * Created by bernat on 3/09/17.
 */
function pathData() {
    var pathItems = window.location.pathname.split('/');


    this.module = pathItems[2];

    this.page = pathItems[pathItems.length - 1];
    return this;
}

$(document).ready(function () {

    //sidebar
    var path = pathData();
    var menuOption = "";
    console.log(path.module);
    if (path.module === 'centros') {
        menuOption = "#menu-centros";
    } else if (path.module === 'companyies') {
        menuOption = "#menu-companies";
    } else if (path.module === 'system') {
        console.log("link_page: " + path.page);
        if (path.page === "index.php" || path.page === "") {
            menuOption = "#menu-dashBoard";

        } else if (path.page === "admin_users.php") {
            menuOption = "#menu-users";

        } else if (path.page === "roles.php") {
            $("#sidebar-system").find("#menu-config").collapse();
            menuOption = "#menu-permissions";

        } else if (path.page === "admin_pages.php") {
            $("#sidebar-system").find("#menu-config").collapse();
            menuOption = "#menu-pages";

        } else if (path.page === "email_settings.php") {
            $("#sidebar-system").find("#menu-config").collapse();
            menuOption = "#menu-mailSettings";
        } else {
            console.log("pagina no configurada al sidebar.")
        }
    }


    $("#sidebar-system").find(menuOption).addClass('active')
});
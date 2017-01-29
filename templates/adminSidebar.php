<ul class="nav nav-sidebar">
    <li>
        <h3 href="/" style="padding: 10px;">Lokaal manager</h3>
    </li>
    <li <?php echo ($page == 'res') ? "class='active'" : ""; ?> >
        <a href="/admin/reservering.php">Lokaal reserveren</a>
    </li>
    <li <?php echo ($page == 'usr') ? "class='active'" : ""; ?> >
        <?php echo ($_SESSION['user_group'] == 1) ? '<a href="/admin/gebruikers.php">gebruikers beheren</a>' : ""; ?>
    </li>
    <li <?php echo ($page == 'lok') ? "class='active'" : ""; ?> >
        <?php echo ($_SESSION['user_group'] == 1) ? '<a href="/admin/lokalen.php">lokalen beheren</a>' : ""; ?>
    </li>
    <li>
        <a href="/">Ga naar homepage</a>
    </li>
    <li>
        <?php echo ($_SESSION['user_group'] == 1) ? '<a href="http://84.30.147.189/phpmyadmin">phpMyAdmin</a>' : ""; ?>
    </li>
    <li>
        <br><br><br>
    </li>
    <li>
        <a href="/admin/logout.php">Uitloggen</a>
    </li>
</ul>
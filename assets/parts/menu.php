
            <nav id="mainMenu">
                <div id="floater">
                    <h1><a href="index.php">Daniel Ljungqvist</a></h1>
                    <i class="fas fa-bars"></i>
                    <div id="responsiveDesign">
                        <ul id="menuList" class="block">
                            <li><a href="section.php?type=0">guides</a></li>
                            <li><a href="section.php?type=1">articles</a></li>
                            <li><a href="about.php">about me</a></li>
                            <li><a href="req.php">support</a></li>
                            <li></li>
                        </ul>
                        <div id="theme">
                            <?php if(is_theme('light')): ?>
                                <a href="<?= build_theme_link('dark'); ?>" class="<?= is_theme('dark') ? 'themeselected' : '' ?>">
                                    <i class="fal fa-toggle-off"></i>
                                    Dark
                                </a>
                            <?php else: ?>
                                <a href="<?= build_theme_link('light'); ?>" class="<?= is_theme('light') ? 'themeselected' : '' ?>">
                                <i class="fal fa-toggle-on"></i>
                                    Dark
                                </a>
                            <?php endif; ?>
                        </div>
                </div>
                </div>
            </nav>
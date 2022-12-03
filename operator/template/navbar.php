<ul class="nav">
<li<?php if ($page == '') echo ' class="active"';?>><a href="<?php echo BASE_URL;?>"><i class="fas fa-cubes"></i> <p><?php echo $jkl["m"];?></p></a></li>
<li<?php if ($page == 'uonline') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('uonline');?>"><i class="fas fa-eye"></i> <p><?php echo $jkl["g122"];?></p></a></li>
<?php if (jak_get_access("leads", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'leads') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('leads');?>"><i class="fas fa-comments-alt"></i> <p><?php echo $jkl["m1"];?></p></a></li>
<?php } if (jak_get_access("off_all", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'contacts') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('contacts');?>"><i class="fas fa-mail-bulk"></i> <p><?php echo $jkl["m22"];?></p></a></li>
<?php } ?>
<li<?php if (in_array($page, array('answers', 'groupchat', 'widget'))) echo ' class="active"';?>>
<a data-toggle="collapse" href="#navcms">
              <i class="fas fa-file-alt"></i>
              <p>
                <?php echo $jkl["m33"];?>
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse<?php if (in_array($page, array('answers', 'groupchat', 'widget'))) echo ' show';?>" id="navcms">
            <ul class="nav">
<?php if (jak_get_access("answers", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'answers') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('answers');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m20"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m20"];?></span>
</a></li>
<?php } if ((($jakhs['hostactive'] == 1 && $jakhs['groupchat'] == 1) || $jakhs['hostactive'] == 0) && jak_get_access("groupchat", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'groupchat') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('groupchat');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m29"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m29"];?></span>
</a></li>
<?php } if (jak_get_access("widget", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'widget') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('widget');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m26"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m26"];?></span>
</a></li>
<?php } ?>
</ul>
</div>
</li>

<li<?php if (in_array($page, array('departments', 'answers', 'response', 'bot', 'proactive', 'files', 'buttons', 'blacklist'))) echo ' class="active"';?>>
<a data-toggle="collapse" href="#navgeneralsettings">
              <i class="fas fa-sliders-h"></i>
              <p>
                <?php echo $jkl["m32"];?>
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse<?php if (in_array($page, array('departments', 'response', 'bot', 'proactive', 'files', 'buttons', 'blacklist'))) echo ' show';?>" id="navgeneralsettings">
            <ul class="nav">
<?php if (jak_get_access("departments", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'departments') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('departments');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m9"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m9"];?></span>
</a></li>
<?php } if (jak_get_access("responses", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'response') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('response');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m3"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m3"];?></span>
</a></li>
<?php } if (jak_get_access("bot", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'bot') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('bot');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m23"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m23"];?></span>
</a></li>
<?php } if (jak_get_access("proactive", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'proactive') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('proactive');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m18"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m18"];?></span>
</a></li>
<?php } if (jak_get_access("files", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'files') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('files');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m2"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m2"];?></span>
</a></li>
<?php } if (JAK_SUPERADMINACCESS){?>
<li<?php if ($page == 'buttons') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('buttons');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["g71"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["g71"];?></span>
</a></li>
<?php } if (jak_get_access("blacklist", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'blacklist') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('blacklist');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m27"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m27"];?></span>
</a></li>
<?php } ?>
</ul>
</div>
</li>

<li<?php if (in_array($page, array('statistics', 'logs', 'chats'))) echo ' class="active"';?>>
<a data-toggle="collapse" href="#navstatistics">
              <i class="fas fa-analytics"></i>
              <p>
                <?php echo $jkl["m10"];?>
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse<?php if (in_array($page, array('statistics', 'logs', 'chats'))) echo ' show';?>" id="navstatistics">
            <ul class="nav">
<?php if (jak_get_access("statistic", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'statistics') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('statistics');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m10"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m10"];?></span>
</a></li>
<?php } if (jak_get_access("logs", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'logs') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('logs');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m6"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m6"];?></span>
</a></li>
<?php } if (jak_get_access("ochat", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'chats') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('chats');?>">
	<span class="sidebar-mini-icon"><?php echo $jkl["m14"][0];?></span>
    <span class="sidebar-normal"> <?php echo $jkl["m14"];?></span>
</a></li>
<?php } ?>
</ul>
</div>
</li>

<li<?php if ($page == 'users') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('users');?>"><i class="fas fa-user-circle"></i> <p><?php echo $jkl["m4"];?></p></a></li>
<?php if (jak_get_access("settings", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS) || jak_get_access("blocklist", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){;?>
<li<?php if ($page == 'settings') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('settings');?>"><i class="fas fa-cog"></i> <p><?php echo $jkl["m5"];?></p></a></li>
<?php } if (jak_get_access("maintenance", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)){?>
<li<?php if ($page == 'maintenance') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('maintenance');?>"><i class="fas fa-wrench"></i> <p><?php echo $jkl["m19"];?></p></a></li>
<?php } if ($jakhs['hostactive'] == 1 && !empty(JAKDB_MAIN_NAME)) { ?>
<li<?php if ($page == 'tickets') echo ' class="active"';?>><a href="<?php echo JAK_rewrite::jakParseurl('tickets');?>"><i class="fas fa-ticket"></i> <p><?php echo $jkl["m31"];?></p></a></li>
<?php } ?>
</ul>
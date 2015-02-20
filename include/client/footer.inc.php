        </div>
    </div>
    <div id="footer">
        <p>Copyright &copy; <?php echo date('Y'); ?> <?php echo (string) $ost->company ?: 'dqserv.com'; ?> - All rights reserved.</p>
        <a id="poweredBy" href="http://dqserv.com" target="_blank"><?php echo __('Helpdesk software - powered by DQSupport'); ?></a>
    </div>
<div id="overlay"></div>

<?php
if (($lang = Internationalization::getCurrentLanguage()) && $lang != 'en_US') { ?>
    <script type="text/javascript" src="ajax.php/i18n/<?php
        echo $lang; ?>/js"></script>
<?php } ?>
</body>
</html>
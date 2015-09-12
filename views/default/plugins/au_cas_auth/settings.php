<?php
    /**
	 * Elgg LDAP authentication
	 * 
	 * @package ElggLDAPAuth
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Misja Hoebe <misja@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com
	 */
?>
<fieldset style="border: 1px solid black; padding: 15px; margin: 0 10px 0 10px;">
<legend><?php echo elgg_echo('cas:settings:local:label')?></legend>
  <label><?php echo elgg_echo('cas:settings:local:enable_local_login'); ?></label> 
  <div class="example"><?php echo elgg_echo('cas:settings:local:help')?></div>
  <select name="params[enable_local_login]">
  <option value="true" <?php if ($vars['entity']->enable_local_login == 'true') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes')?></option>
  <option value="false" <?php if (($vars['entity']->enable_local_login == 'false') || (!isset($vars['entity']->enable_local_login))) echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no')?></option>
  </select>
</fieldset>


<fieldset style="border: 1px solid black; padding: 15px; margin: 0 10px 0 10px;">
<legend>CAS Settings</legend>

    <fieldset style="border: 1px solid black; padding: 15px; margin: 0 10px 0 10px;">
    <legend><?php echo elgg_echo('cas:settings:ldap:label:host')?></legend>
    <label for="params[cashostname]"><?php echo elgg_echo('cas:settings:cas:label:hostname');?></label><br/>
      <div class="example"><?php echo elgg_echo('cas:settings:cas:help:hostname');?></div>
      <input type="text" name="params[cashostname]" value="<?php if (empty($vars['entity']->cashostname)) {echo "";} else {echo $vars['entity']->cashostname;}?>"/><br/>
    
    <label for="params[casport]"><?php echo elgg_echo('cas:settings:cas:label:port')?></label><br/>
      <div class="example"><?php echo elgg_echo('cas:settings:cas:help:port')?></div>
      <input type="text" name="params[casport]" value="<?php if (empty($vars['entity']->casport)) { echo "443";} else { echo $vars['entity']->casport;}?>"/><br/>
      
    <label for="params[casbaseuri]"><?php echo elgg_echo('cas:settings:cas:label:baseuri')?></label><br/>
      <div class="example"><?php echo elgg_echo('cas:settings:cas:help:baseuri')?></div>
      <input type="text" name="params[casbaseuri]" value="<?php if (empty($vars['entity']->casbaseuri)) { echo "/cas";} else { echo $vars['entity']->casbaseuri;}?>"/><br/>
      
    <label for="params[cas_server_ca_cert_path]"><?php echo elgg_echo('cas:settings:cas:label:certpath'); ?></label>
    	<div class="example"><?php echo elgg_echo('cas:settings:cas:help:certpath'); ?></div>
    	<?php 
    	echo elgg_view('input/text', array('name' => "params[cas_server_ca_cert_path]", 'value' => $vars['entity']->cas_server_ca_cert_path));
    	?> 
    </fieldset>


    <fieldset style="border: 1px solid black; padding: 15px; margin: 0 10px 0 10px;">
    <legend><?php echo elgg_echo('cas:settings:cas:settings')?></legend>
            <label for="params[casadminuser]"><?php echo elgg_echo('cas:settings:cas:adminuser')?></label><br/>
            <div class="example"><?php echo elgg_echo('cas:settings:cas:help:casadminuser')?></div>
      <input type="text" name="params[casadminuser]" value="<?php if (empty($vars['entity']->casadminuser)) { echo "";} else { echo $vars['entity']->casadminuser;}?>"/><br/>
    
            <label for="params[user_create]"><?php echo elgg_echo('cas:settings:cas:label:createuser');?></label><br/>
            <div class="example"><?php echo elgg_echo('cas:settings:cas:help:createuser')?></div>
            <select name="params[user_create]">
                <option value="on" <?php if ($vars['entity']->user_create == 'on') echo " selected=\"selected\" "; ?>>Enabled</option>
                <option value="off" <?php if ($vars['entity']->user_create == 'off') echo " selected=\"selected\" "; ?>>Disabled</option>
            </select>
            <br/>
    <label for="params[caslogout]"><?php echo elgg_echo('cas:settings:cas:label:logout');?></label><br/>
            <div class="example"><?php echo elgg_echo('cas:settings:cas:help:logout')?></div>
            <select name="params[caslogout]">
                <option value="on" <?php if ($vars['entity']->caslogout == 'on') echo " selected=\"selected\" "; ?>>Enabled</option>
                <option value="off" <?php if ($vars['entity']->caslogout == 'off') echo " selected=\"selected\" "; ?>>Disabled</option>
            </select>        
    
    </fieldset>

</fieldset>


<fieldset style="border: 1px solid black; padding: 15px; margin: 0 10px 0 10px;">
<legend>LDAP Settings</legend>
<p>
    <fieldset style="border: 1px solid; padding: 15px; margin: 0 10px 0 10px">
        <legend><?php echo elgg_echo('cas:settings:ldap:label:host');?></legend>
        
        <label for="params[ldaphostname]"><?php echo elgg_echo('cas:settings:ldap:label:hostname');?></label><br/>
        <div class="example"><?php echo elgg_echo('cas:settings:ldap:help:hostname');?></div>
        <input type="text" name="params[ldaphostname]" value="<?php echo $vars['entity']->ldaphostname;?>"/><br/>
        
        <label for="params[port]"><?php echo elgg_echo('cas:settings:ldap:label:port');?></label><br/>
        <div class="example"><?php echo elgg_echo('cas:settings:ldap:help:port');?></div>
        <input type="text" name="params[ldapport]" value="<?php if (empty($vars['entity']->ldapport)) {echo "389";} else {echo $vars['entity']->ldapport;}?>"/><br/>

        <label for="params[version]"><?php echo elgg_echo('cas:settings:ldap:label:version');?></label><br/>
        <div class="example"><?php echo elgg_echo('cas:settings:ldap:help:version');?></div>
        <select name="params[ldapversion]">
            <option value="1" <?php if ($vars['entity']->ldapversion == 1) echo " selected=\"selected\" "; ?>>1</option>
            <option value="2" <?php if ($vars['entity']->ldapversion == 2) echo " selected=\"selected\" "; ?>>2</option>
            <option value="3" <?php if ((!$vars['entity']->ldapversion) || ($vars['entity']->ldapversion == 3)) echo " selected=\"selected\" "; ?>>3</option>
        </select>
    </fieldset>
</p>
<p>
    <fieldset style="border: 1px solid; padding: 15px; margin: 0 10px 0 10px">
        <legend><?php echo elgg_echo('cas:settings:ldap:label:connection_search');?></legend>

        <label for="params[ldap_bind_dn]"><?php echo elgg_echo('cas:settings:ldap:label:ldap_bind_dn');?></label><br/>
        <div class="example"><?php echo elgg_echo('cas:settings:ldap:help:ldap_bind_dn');?></div>
        <input type="text" size="50" name="params[ldap_bind_dn]" value="<?php echo $vars['entity']->ldap_bind_dn;?>"/><br/>

        <label for="params[ldap_bind_pwd]"><?php echo elgg_echo('cas:settings:ldap:label:ldap_bind_pwd');?></label><br/>
        <div class="example"><?php echo elgg_echo('cas:settings:ldap:help:ldap_bind_pwd');?></div>
        <input type="password" name="params[ldap_bind_pwd]" value="<?php echo $vars['entity']->ldap_bind_pwd;?>"/><br/>

        <label for="params[basedn]"><?php echo elgg_echo('cas:settings:ldap:label:basedn');?></label><br/>
        <div class="example"><?php echo elgg_echo('cas:settings:ldap:help:basedn');?></div>
        <input type="text" size="50" name="params[basedn]" value="<?php echo $vars['entity']->basedn;?>"/><br/>

        <label for="params[filter_attr]"><?php echo elgg_echo('cas:settings:ldap:label:filter_attr');?></label><br/>
        <div class="example"><?php echo elgg_echo('cas:settings:ldap:help:filter_attr');?></div>
        <input type="text" size="50" name="params[filter_attr]" value="<?php echo $vars['entity']->filter_attr;?>"/><br/>

        <label for="params[search_attr]"><?php echo elgg_echo('cas:settings:ldap:label:search_attr');?></label><br/>
        <div class="example"><?php echo elgg_echo('cas:settings:ldap:help:search_attr');?></div>
        <input type="text" size="50" name="params[search_attr]" value="<?php echo $vars['entity']->search_attr;?>"/><br/>


    </fieldset>
</p>
</fieldset>
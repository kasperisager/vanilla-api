<?php
/**
 * API Settings View
 *
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */

if (!defined('APPLICATION')) exit(); ?>

<style class="text/css">
   .Endpoint {
      padding: 10px;
      background: #F1F1F1;
      font-size: 16px;
      font-family: "Courier New", Courier, monospace;
   }
</style>

<div class="Help Aside">
   <h2><?php echo T('Need More Help?') ?></h2>
   <ul>
      <?php
      echo Wrap(Anchor('Visit the API Explorer',      'api'), 'li');
      echo Wrap(Anchor('Read the API documentation',  'api/wiki'), 'li');
      ?>
   </ul>
</div>

<h1><?php echo T($this->Data('Title')) ?></h1>

<?php
$Form = $this->Form;
echo $Form->Open();
echo $Form->Errors();
?>

<ul>
   <li>
      <?php echo $Form->Label('Endpoint', 'Endpoint'); ?>
      <div class="Info">
         <p><?php echo T('You can access your forum\'s Application Interface (API) through this endpoint URL') ?></p>
      </div>
      <div class="Endpoint">
         <blockquote><?php echo Gdn::Request()->Domain() ?>/api/</blockquote>
      </div>
   </li>
   <li>
      <?php echo $Form->Label('Application Secret', 'Secret'); ?>
      <div class="Info">
         <p><?php echo T('This is the Application Secret used for signature based authentication. <b>Keep it secret!</b>') ?></p>
         <small><?php echo sprintf(T('Clicking "Re-generate" will generate a new UUID v4 key. Please refer to %s for more information'), Anchor(T('this article'), 'http://en.wikipedia.org/wiki/Uuid')) ?></small>
      </div>
      <?php
      echo $Form->TextBox('Secret', array('class' => 'InputBox BigInput', 'readonly' => 'readonly'));
      echo $Form->Button('Re-generate');
      ?>
   </li>
</ul>

<?php echo $Form->Close() ?>
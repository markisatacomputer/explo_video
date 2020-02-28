<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<h1><?php print $title; ?></h1>

<div class="subjects">
  <div class="grid-100 grid-parent">
  <?php foreach ($subjects as $i => $subject): ?>
    <div class="grid-25 subject">
      <div class="image">
        <a href="<?php print $subject['link']; ?>"><img src="<?php print $subject['image']; ?>" /></a>
      </div>
      <div class="name">
        <a href="<?php print $subject['link']; ?>">
          <h3><?php print $subject['name']; ?></h3>
        </a>
      </div>
      <div class="count"><?php print $subject['count']; ?> Programs</div>
    </div>
    <?php if (in_array($i, array(3,7))) : ?>
  </div>
  <div class="grid-100 grid-parent">
    <?php endif; ?>
  <?php endforeach; ?>
  </div>
</div>

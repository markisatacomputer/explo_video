<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>

<h1>Featured Video Collections</h1>

<div class="grid-100 grid-parent video-collections-page-content">
  <div class="grid-33 intro">
    <p><?php print $description; ?></p>

    <div><a href="#AllCollections">See all from A to Z</a></div>
  </div>
  <?php foreach ($collections as $id => $collection): ?>
    <div class="grid-33">
      <div class="clearfix">
        <div class="image">
          <a href="/<?php print $collection['link']; ?>">
            <img src="<?php print $collection['image']; ?>" />
          </a>
        </div>
        <div class="title">
          <a href="/<?php print $collection['link']; ?>">
            <h3><?php print $collection['name']; ?></h3>
          </a>
        </div>
        <div class="description"><?php print $collection['description']; ?></div>
      </div>
    </div>
    <?php if (in_array($id, array(1,4,7))) : ?>
</div>
<div class="grid-100 grid-parent video-collections-page-content">
    <?php endif; ?>
  <?php endforeach; ?>
</div>

<a name="AllCollections"></a>
<?php print $all_collections; ?>

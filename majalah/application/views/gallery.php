<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Section: wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">

            <!--Check breadcrumb active-->
            <?php if ($page->breadcrumb_active == 1): ?>
                <div class="col-sm-12 page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo lang_base_url(); ?>"><?php echo trans("breadcrumb_home"); ?></a>
                        </li>

                        <li class="breadcrumb-item active"><?php echo html_escape($page->title); ?></li>
                    </ol>
                </div>
            <?php else: ?>
                <div class="col-sm-12 page-breadcrumb"></div>
            <?php endif; ?>

            <div class="col-sm-12">
                <div class="content page-about page-gallery">

                    <!--Check page title active-->
                    <?php if ($page->title_active == 1): ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <h1 class="page-title"><?php echo html_escape($page->title); ?></h1>
                            </div>
                        </div>
                    <?php endif; ?>


                    <!--Portfolio Filters-->

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="filters text-center">
                                <label data-filter="" class="btn btn-primary active">
                                    <input type="radio" name="options"> <?php echo trans("all"); ?>
                                </label>

                                <?php foreach ($gallery_categories as $category): ?>
                                    <label data-filter="<?php echo $category->name; ?>" class="btn btn-primary">
                                        <input type="radio" name="options"> <?php echo $category->name; ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row row-masonry">
                        <div id="masonry" class="gallery">
                            <!--Load Items-->
                            <?php foreach ($gallery_images as $item): ?>
                                <div data-filter="<?php echo html_escape($item->category_name); ?>" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 gallery-item">
                                    <div class="item-inner">
                                        <a class="image-popup lightbox" href="<?php echo base_url() . $item->path_big; ?>" data-effect="mfp-zoom-out" title="<?php echo html_escape($item->title); ?>">
                                            <img src="<?php echo base_url() . html_escape($item->path_small); ?>" alt="<?php echo html_escape($item->title); ?>" class="img-responsive"/>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.Section: wrapper -->
<!-- Gallery -->
<script src="<?php echo base_url(); ?>assets/vendor/masonry-filter/imagesloaded.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/masonry-filter/masonry-3.1.4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/masonry-filter/masonry.filter.js"></script>
<script>
$(document).ready(function(a){a(".image-popup").magnificPopup({type:"image",titleSrc:function(b){return b.el.attr("title")+"<small></small>"},image:{verticalFit:true,},gallery:{enabled:true,navigateByImgClick:true,preload:[0,1]},removalDelay:100,fixedContentPos:true,})});$(document).ready(function(a){a(".image-popup-no-title").magnificPopup({type:"image",image:{verticalFit:true,},gallery:{enabled:false,navigateByImgClick:true,preload:[0,1]},removalDelay:100,fixedContentPos:true,})});$(document).ready(function(a){a(".single-image-popup").magnificPopup({type:"image",titleSrc:function(b){return b.el.attr("title")+"<small></small>"},image:{verticalFit:true,},gallery:{enabled:false,navigateByImgClick:true,preload:[0,1]},removalDelay:100,fixedContentPos:true,})});$(document).ready(function(){$(".filters .btn").click(function(){$(".filters .btn").removeClass("active");$(this).addClass("active")});$(function(){var a=$("#masonry");a.imagesLoaded(function(){a.masonry({gutterWidth:0,isAnimated:true,itemSelector:".gallery-item"})});$(".filters .btn").click(function(b){b.preventDefault();var c=$(this).attr("data-filter");a.masonryFilter({filter:function(){if(!c){return true}return $(this).attr("data-filter")==c}})})})});
</script>
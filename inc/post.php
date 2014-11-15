<?php
/* 시작 */
function akaiv_before_post($post = true) {
  if ( $post ) : ?>
  <article <?php post_class(); ?>><?php
  else : ?>
    <article class="hentry"><?php
  endif;
}

/* 끝 */
function akaiv_after_post() { ?>
  </article><?php
}

/* 제목 */
function akaiv_the_title() {
  $title = trim(get_the_title());
  if ( ! $title ) $title = '(제목이 없는 글)';
  echo $title;
}

/* 썸네일 */
function akaiv_post_thumbnail() {
  if ( post_password_required() && ! is_singular() ) : /* 비밀 글 */ ?>
    <a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php akaiv_the_post_thumbnail_placeholder( 'thumbnail', 'thumbnail-lock' ); ?></a><?php
    return;
  endif;

  if ( is_singular() ) : /* 글, 페이지, 첨부파일 */
    if ( has_post_thumbnail() ) : ?>
      <div class="post-thumbnail">
        <?php the_post_thumbnail( 'full' ); ?>
      </div><?php
    endif;

  else : /* 보관함 */ ?>
    <a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php
      if ( has_post_thumbnail() ) :
        the_post_thumbnail( 'thumbnail' );
      else :
        akaiv_the_post_thumbnail_placeholder( 'thumbnail' );
      endif; ?>
    </a><?php

  endif;
}

/* 썸네일: 플레이스홀더 */
function akaiv_the_post_thumbnail_placeholder($size = 'thumbnail', $filename = 'thumbnail-post', $ext = 'png') {
  $src    = get_template_directory_uri().'/images/'.$filename.'.'.$ext;
  $srcset = get_template_directory_uri().'/images/'.$filename.'@2x.'.$ext.' 2x';
  $alt = get_the_title();
  list( $src_width, $src_height ) = getimagesize( $src );
  list( $width, $height ) = image_constrain_size_for_editor( $src_width, $src_height, $size );
  $hwstring = image_hwstring( $width, $height );
  $class = 'attachment-'.$size.' wp-post-image';
  echo '<img src="'.$src.'" alt="'.$alt.'" '.$hwstring.'class="'.$class.'" srcset="'.$srcset.'">';
}

/* 썸네일: 소스 */
function akaiv_get_post_thumbnail_src($size = 'full') {
  $post_thumbnail_id = get_post_thumbnail_id();
  $image = wp_get_attachment_image_src( $post_thumbnail_id, $size );
  list( $src, $width, $height ) = $image;
  return $src;
}

/* 썸네일: 레티나 */
function akaiv_the_post_thumbnail_srcset($size1x, $size2x) {
  $attr = array( 'srcset' => akaiv_get_post_thumbnail_src( $size2x ).' 2x' );
  the_post_thumbnail( $size1x, $attr );
}

/* 메타 */
function akaiv_post_meta($meta = null) {
  if ( ! $meta ) return;

  if ( $meta == 'category' ) : ?>
    <span class="cat-links"><i class="fa fa-fw fa-folder-open"></i> <?php echo get_the_category_list( ', ' ); ?></span><?php

  elseif ( $meta == 'tag' ) :
    if ( has_tag() ) : ?>
      <span class="tag-links"><i class="fa fa-fw fa-tag"></i> <?php the_tags( '', ', ', '' ); ?></span><?php
    endif;

  elseif ( $meta == 'date' ) : ?>
    <span class="posted-on"><i class="fa fa-fw fa-clock-o"></i> <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ) ?></time></a></span><?php

  elseif ( $meta == 'author' ) : ?>
    <span class="author"><i class="fa fa-fw fa-user"></i> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo get_the_author(); ?></a></span><?php

  endif;
}

/* 편집 링크 */
function akaiv_edit_post_link() {
  if ( is_single() ) :
    edit_post_link( '편집', '<span class="edit-link"><i class="fa fa-fw fa-pencil"></i> ', '</span>' );
  else :
    edit_post_link( '편집', '<div class="text-right"><span class="edit-link">', '</span></div>' );
  endif;
}

/* 내비게이션 버튼 */
function akaiv_post_nav() {
  $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
  $next     = get_adjacent_post( false, '', false );
  if ( ! $next && ! $previous ) return; ?>
  <nav class="navigation post-navigation" role="navigation">
    <h1 class="screen-reader-text"><?php echo 'Post navigation'; ?></h1>
    <div class="nav-links"><?php
      if ( is_attachment() ) : /* 첨부파일 페이지일 때 */ ?>
        <div class="published-in"><?php
          previous_post_link( '%link', '<i class="fa fa-fw fa-folder-open"></i> 발행 위치: ' . '%title' ); ?>
        </div><?php
      else : /* 첨부파일 페이지가 아닐 때 */
        previous_post_link( '%link', '<i class="fa fa-fw fa-angle-left"></i> 이전 글');
        next_post_link( '%link', '다음 글 <i class="fa fa-fw fa-angle-right"></i>' );
      endif; ?>
    </div>
  </nav><?php
}

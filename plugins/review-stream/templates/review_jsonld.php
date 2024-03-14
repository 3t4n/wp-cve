<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "[[business_category]]",
  "image": "[[image_url]]",
  "name": "[[name]]",
  "review": {
    "@type": "Review",
    "reviewRating": {
      "@type": "Rating",
      "ratingValue": "[[rating]]"
    },
    "author": {
      "@type": "Person",
      "name": "[[attribution]]"
    },
    "datePublished": "[[reviewdate]]",
    "reviewBody": [[escaped_snippet]]
  }
}

</script>
<div class="review">
    <div class="review-meta">
        <div class="review-date">
            [[reviewdate]]
        </div>
        <div class="review-rating">
            [[ratingwidget]]
        </div>
    </div>
    <div class="review-text">
        <div class="review-text-inner">
            [[snippet]]
        </div>
    </div>
    <div class="review-source">
        <span class="icon-link-[[category]]"></span>
    </div>
    <div class="review-attribution">
        <div class="review-name">
            [[attribution]]
        </div>
    </div>
    <div class="review-link">
        [[link]]
    </div>
</div>

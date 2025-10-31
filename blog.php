<?php

include 'includes/header.php';
include 'includes/db.php';

$sql = "SELECT id, title, content, author, created_at FROM blog_posts ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="page-container">
    <h1>FitZone Blog</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="blog-list">
            <?php while ($post = mysqli_fetch_assoc($result)): ?>
                <article class="blog-post">
                    <h2><?= htmlspecialchars($post['title']) ?></h2>
                    <small>Published on <?= date('F j, Y', strtotime($post['created_at'])) ?></small>
                    <p class="text"><?= nl2br(htmlspecialchars($post['content'])) ?>...</p>
                    <!-- add shortening and full view option in javascript -->
                    <h1>By <?= $post['author'] ?></h1>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No blog posts available yet. Check back soon!</p>
    <?php endif; ?>
</div>

<script>
  const charLimit = 100; //character limit

  document.querySelectorAll(".text").forEach(function(textElement) {
    const fullText = textElement.innerHTML.trim();
    if (fullText.length > charLimit) {
      const visibleText = fullText.substring(0, charLimit).trim();
      const hiddenText = fullText.substring(charLimit).trim();

      textElement.innerHTML = 
        `${visibleText}<span class="dots">...</span><span class="more" style="display:none;">${hiddenText}</span> <span class="read-more">Read More</span>`;

      const dots = textElement.querySelector(".dots");
      const moreText = textElement.querySelector(".more");
      const btn = textElement.querySelector(".read-more");

      btn.addEventListener("click", function() {
        const isHidden = moreText.style.display === "none";

        if (isHidden) {
          dots.style.display = "none";
          moreText.style.display = "inline";
          btn.innerHTML = "See Less";
        } else {
          dots.style.display = "inline";
          moreText.style.display = "none";
          btn.innerHTML = "Read More";
        }
      });
    }
  });
</script>



<?php include 'includes/footer.php'; ?>

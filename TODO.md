# Fix Page Reload on Like/Unlike and Comment Actions

## Information Gathered

-   Like/Unlike post: Forms in `resources/views/posts/show.blade.php` and `resources/views/feed/index.blade.php` submit to `ReactionController@store` and `destroy`, causing page reloads.
-   Like/Unlike comment: Forms in `resources/views/posts/show.blade.php` and `resources/views/feed/index.blade.php` submit to `CommentController@like` and `unlike`, causing page reloads.
-   Post Comment: Form in `resources/views/posts/show.blade.php` submits to `CommentController@store`, causing page reload.
-   Controllers support JSON responses for AJAX requests.
-   UI elements like reaction counts and comment lists need dynamic updates without full page reload.

## Plan

1. Add data attributes and classes to forms for AJAX identification.
2. Add JavaScript event listeners to handle form submissions via fetch, preventing default reloads.
3. Update reaction/comment counts dynamically after successful AJAX responses.
4. For comment posting, append new comment HTML to the page after successful submission.
5. Ensure fallback to normal form submission if JavaScript fails.

## Dependent Files to be Edited

-   `resources/views/posts/show.blade.php`: Add classes/data attributes to forms, add JavaScript for AJAX handling.
-   `resources/views/feed/index.blade.php`: Similar changes for feed page.

## Followup Steps

-   Test like/unlike actions on posts and comments to ensure no page reload and counts update.
-   Test posting comments to ensure no reload and comment appears.
-   Verify on both post show and feed pages.

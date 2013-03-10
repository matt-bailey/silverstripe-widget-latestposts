<% control PostCategory %>
<h4>$Title</h4>
<% end_control %>
<% if LatestPosts %>
<% control LatestPosts %>
<article>
    <p class="date">$Date.Long</p>
    <h4><a href="$Link" title="$Title">$Title</a></h4>
    <p class="summary">$Content.FirstParagraph()</p>
    <p>
        <a href="$Link" title="$Title">Read Article</a>
    </p>
</article>
<% end_control %>
<% end_if %>

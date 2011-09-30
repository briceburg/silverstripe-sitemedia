<div class="entry">
	
	<% if HasSiteMedia %>
		<h1>Featured Media</h1>
		<% include SiteMedia %>
	<% end_if %>
	
	<div class="entry-text">
	
		$Content
		
		<h1>Recent Photos</h1>
		<% control PaginatedSiteMedia(SitePhoto) %>
		$Photo
		
		<% end_control %>
		
		<h1>Recent Videos</h1>
		<% control PaginatedSiteMedia(SiteVideo) %>
		$Title
		
		<% end_control %>
		
		
		
		
	
	</div>
</div>
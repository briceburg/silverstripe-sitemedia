<% cached 'site-media', My.Class, My.ID, Aggregate(SiteMedia).Max(LastEdited) %>
	<% control My %>
		<% include SiteMedia %>
	<% end_control %>
<% end_cached %>
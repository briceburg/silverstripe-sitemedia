<% if HasMedia %>
	<div class="site-media-gallery">
		<% if MoreThanOneMedia %>
			<div class="site-media-nav-next"><div></div></div>
			<div class="site-media-nav-prev"><div></div></div>
			
			<div class="site-media-nav-button-container">
				<% control Media %>
					<a href="#" class="site-media-button <% if First %>active<% end_if %>">$Pos</a>
				<% end_control %>
				<div class="cl">&nbsp;</div>
			</div>
			
		<% end_if %>
			
		<div id="site-media-container">
		
			<div id="site-media-items">
				<% control Media %>
				
					<div class="site-media-item">
					
						<% if Type == Photo %>
						
							$File.GalleryImage
							
						<% else %>
							$ReadyVideo
							<a href="$File.Link" class="digome-player {isStream: false, width: 625, height: 352}">Loading Video</a>
						<% end_if %>
						
					</div>
		
				<% end_control %>
			</div>
		
		</div>
	</div>
	
<% end_if %>

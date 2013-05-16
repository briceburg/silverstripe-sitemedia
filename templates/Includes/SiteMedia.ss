<% if HasSiteMedia %>
	
	<div class="site-media-gallery">
		<% if HasMultipleSiteMedia %>
			<div class="site-media-nav-next"><div></div></div>
			<div class="site-media-nav-prev"><div></div></div>
			
			<div class="site-media-nav-button-container">
				<% loop SiteMedia %>
					<a href="#" class="site-media-button <% if First %>active<% end_if %>">$Pos</a>
				<% end_loop %>
				<div class="cl">&nbsp;</div>
			</div>
			
		<% end_if %>
			
		<div id="site-media-container">
		
			<div id="site-media-items">
				<% loop SiteMedia %>
				
					<div class="site-media-item">
					
						<% if Type == SomeMediaType %>
							Example override...
							
							NOTE: you can override also override the MediaMarkup
							      by adding the type's template to the Media
							      directory of your theme. 
							      (e.g. themes/MyTheme/Media/SitePhoto.ss)
							      
							NOTE: you can override this markup by adding
							      SiteMedia.ss to the Includes directory of
							      your theme.
							      
						<% else %>
							$MediaMarkup
						<% end_if %>
						
					</div>
		
				<% end_loop %>
			</div>
		
		</div>
		
		
	</div>
	
<% end_if %>

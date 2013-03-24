[{capture append="oxidBlock_content"}]
	[{include file="widget/product/list.tpl" type=$oViewConf->getViewThemeParam('sStartPageListDisplayType') head="Unsere Topangebote" products=$oView->getLandingPageArticles() showMainLink=true}]

	<div class="lpText">
	[{oxifcontent ident=$smarty.get.lp object="oCont"}]
		[{$oCont->oxcontents__oxcontent->value}]
	[{/oxifcontent}]
	</div>
[{/capture}]
[{include file="layout/page.tpl" sidebar="Right"}]
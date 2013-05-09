<?PHP
$filter_mediawiki_wiki_list = array();

$filter_mediawiki_wiki_list[] = array( 'short' => 'wv', 'long' => 'wikiversity', 'description' => 'Wikiversity',
	'lang' => 'ar,cs,de,el,en,es,fi,fr,it,ja,ko,pt,ru,sl,sv',
	'api' => 'https://$lang.wikiversity.org/w/api.php', 'page' => '//$lang.wikiversity.org/wiki/$1', 'type' => 'wikimedia' );

$filter_mediawiki_wiki_list[] = array( 'short' => 'wp', 'long' => 'wikipedia', 'description' => 'Wikipedia',
	'lang' => 'ab,ace,af,ak,als,am,an,ang,ar,arc,arz,as,ast,av,ay,az,ba,bar,bat-smg,bcl,be,be-x-old,bg,bh,bi,bjn,bm,bn,bo,bpy,br,bs,bug,bxr,ca,cbk-zam,cdo,ce,ceb,ch,cho,chr,chy,ckb,co,cr,crh,cs,csb,cu,cv,cy,da,de,diq,dsb,dv,dz,ee,el,eml,en,eo,es,et,eu,ext,fa,ff,fi,fiu-vro,fj,fo,fr,frp,frr,fur,fy,ga,gag,gan,gd,gl,glk,gn,got,gu,gv,ha,hak,haw,he,hi,hif,ho,hr,hsb,ht,hu,hy,hz,ia,id,ie,ig,ii,ik,ilo,io,is,it,iu,ja,jbo,jv,ka,kaa,kab,kbd,kg,ki,kj,kk,kl,km,kn,ko,koi,kr,krc,ks,ksh,ku,kv,kw,ky,la,lad,lb,lbe,lez,lg,li,lij,lmo,ln,lo,lt,ltg,lv,map-bms,mdf,mg,mh,mhr,mi,min,mk,ml,mn,mo,mr,mrj,ms,mt,mus,mwl,my,myv,mzn,na,nah,nap,nds,nds-nl,ne,new,ng,nl,nn,no,nov,nrm,nso,nv,ny,oc,om,or,os,pa,pag,pam,pap,pcd,pdc,pfl,pi,pih,pl,pms,pnb,pnt,ps,pt,qu,rm,rmy,rn,ro,roa-rup,roa-tara,ru,rue,rw,sa,sah,sc,scn,sco,sd,se,sg,sh,si,simple,sk,sl,sm,sn,so,sq,sr,srn,ss,st,stq,su,sv,sw,szl,ta,te,tet,tg,th,ti,tk,tl,tn,to,tpi,tr,ts,tt,tum,tw,ty,udm,ug,uk,ur,uz,ve,vec,vep,vi,vls,vo,wa,war,wo,wuu,xal,xh,xmf,yi,yo,za,zea,zh,zh-classical,zh-min-nan,zh-yue,zu',
	'api' => 'https://$lang.wikipedia.org/w/api.php', 'page' => '//$lang.wikipedia.org/wiki/$1', 'type' => 'wikimedia' );

$filter_mediawiki_wiki_list[] = array( 'short' => 'wb', 'long' => 'wikibooks', 'description' => 'Wikibooks',
	'lang' => 'af,ak,als,ang,ar,as,ast,ay,az,ba,be,bg,bi,bm,bn,bo,bs,ca,ch,co,cs,cv,cy,da,de,el,en,eo,es,et,eu,fa,fi,fr,fy,ga,gl,gn,got,gu,he,hi,hr,hu,hy,ia,id,ie,is,it,ja,ka,kk,km,kn,ko,ks,ku,ky,la,lb,li,ln,lt,lv,mg,mi,mk,ml,mn,mr,ms,my,na,nah,nds,ne,nl,no,oc,pa,pl,ps,pt,qu,rm,ro,ru,sa,se,si,simple,sk,sl,sq,sr,su,sv,sw,ta,te,tg,th,tk,tl,tr,tt,ug,uk,ur,uz,vi,vo,wa,xh,yo,za,zh,zh-min-nan,zu',
	'api' => 'https://$lang.wikibooks.org/w/api.php', 'page' => '//$lang.wikibooks.org/wiki/$1', 'type' => 'wikimedia' );

$filter_mediawiki_wiki_list[] = array( 'short' => 'wikt', 'long' => 'wiktionary', 'description' => 'Wiktionary',
	'lang' => 'ab,af,ak,als,am,an,ang,ar,as,ast,av,ay,az,be,bg,bh,bi,bm,bn,bo,br,bs,ca,ch,chr,co,cr,cs,csb,cy,da,de,dv,dz,el,en,eo,es,et,eu,fa,fi,fj,fo,fr,fy,ga,gd,gl,gn,gu,gv,ha,he,hi,hr,hsb,hu,hy,ia,id,ie,ik,io,is,it,iu,ja,jbo,jv,ka,kk,kl,km,kn,ko,ks,ku,kw,ky,la,lb,li,ln,lo,lt,lv,mg,mh,mi,mk,ml,mn,mo,mr,ms,mt,my,na,nah,nds,ne,nl,nn,no,oc,om,or,pa,pi,pl,pnb,ps,pt,qu,rm,rn,ro,roa-rup,ru,rw,sa,sc,scn,sd,sg,sh,si,simple,sk,sl,sm,sn,so,sq,sr,ss,st,su,sv,sw,ta,te,tg,th,ti,tk,tl,tn,to,tpi,tr,ts,tt,tw,ug,uk,ur,uz,vi,vo,wa,wo,xh,yi,yo,za,zh,zh-min-nan,zu',
	'api' => 'https://$lang.wiktionary.org/w/api.php', 'page' => '//$lang.wiktionary.org/wiki/$1', 'type' => 'wikimedia' );

$filter_mediawiki_wiki_list[] = array( 'short' => 'wn', 'long' => 'wikinews', 'description' => 'Wikinews',
	'lang' => 'ar,bg,bs,ca,cs,de,el,en,eo,es,fa,fi,fr,he,hu,it,ja,ko,nl,no,pl,pt,ro,ru,sd,sq,sr,sv,ta,th,tr,uk,zh',
	'api' => 'https://$lang.wikinews.org/w/api.php', 'page' => '//$lang.wikinews.org/wiki/$1', 'type' => 'wikimedia' );

$filter_mediawiki_wiki_list[] = array( 'short' => 'ws', 'long' => 'wikisource', 'description' => 'Wikisource',
	'lang' => 'ang,ar,as,az,be,bg,bn,br,bs,ca,cs,cy,da,de,el,en,eo,es,et,fa,fi,fo,fr,gl,gu,he,hr,ht,hu,hy,id,is,it,ja,kn,ko,la,li,lt,mk,ml,mr,nl,no,pl,pt,ro,ru,sa,sah,sk,sl,sr,sv,ta,te,th,tr,uk,vec,vi,yi,zh,zh-min-nan',
	'api' => 'https://$lang.wikisource.org/w/api.php', 'page' => '//$lang.wikisource.org/wiki/$1', 'type' => 'wikimedia' );

$filter_mediawiki_wiki_list[] = array( 'short' => 'wq', 'long' => 'wikiquote', 'description' => 'Wikiquote',
	'lang' => 'af,als,am,ang,ar,ast,az,be,bg,bm,br,bs,ca,co,cr,cs,cy,da,de,el,en,eo,es,et,eu,fa,fi,fr,ga,gl,gu,he,hi,hr,hu,hy,id,is,it,ja,ka,kk,kn,ko,kr,ks,ku,kw,ky,la,lb,li,lt,ml,mr,na,nds,nl,nn,no,pl,pt,qu,ro,ru,sa,simple,sk,sl,sq,sr,su,sv,ta,te,th,tk,tr,tt,ug,uk,ur,uz,vi,vo,wo,za,zh,zh-min-nan',
	'api' => 'https://$lang.wikiquote.org/w/api.php', 'page' => '//$lang.wikiquote.org/wiki/$1', 'type' => 'wikimedia' );

$filter_mediawiki_wiki_list[] = array( 'short' => 'voy', 'long' => 'wikivoyage', 'description' => 'Wikivoyage',
	'lang' => 'de,en,es,fr,he,it,nl,pl,pt,ro,ru,sv,uk', 'api' => 'https://$lang.wikivoyage.org/w/api.php',
	'page' => '	//$lang.wikivoyage.org/wiki/$1', 'type' => 'wikimedia' );

$filter_mediawiki_wiki_list[] = array( 'short' => 'wd', 'long' => 'wikidata', 'description' => 'Wikidata',
	'lang' => null, 'api' => 'https://www.wikidata.org/w/api.php',
	'page' => '//www.wikidata.org/wiki/$1', 'type' => 'wikimedia' );

$filter_mediawiki_wiki_list[] = array( 'short' => 'commons', 'long' => 'commons', 'description' => 'Wikimedia Commons',
	'lang' => null, 'api' => 'https://commons.wikimedia.org/w/api.php',
	'page' => '	//commons.wikimedia.org/wiki/$1', 'type' => 'wikimedia' );

$filter_mediawiki_wiki_list[] = array( 'short' => 'meta', 'long' => 'meta', 'description' => 'Wikimedia Meta-Wiki',
	'lang' => null, 'api' => 'https://meta.wikimedia.org/w/api.php',
	'page' => '	//meta.wikimedia.org/wiki/$1', 'type' => 'wikimedia' );
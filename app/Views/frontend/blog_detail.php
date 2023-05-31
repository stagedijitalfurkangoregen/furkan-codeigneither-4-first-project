

<form method = "post" action="<?=base_url("blogs")?>">
    <input name="blog_edit_id" type="hidden" value="<?=$blog['blog_id']?>">
    <label for="">Blog Adı:</label>
    <input name="blog_edit_title" type="text" value="<?=$blog['blog_title']?>">
    <label for="">Blog Açıklaması:</label>
    <input name="blog_edit_description" type="text" value="<?=$blog['blog_description']?>">
    <button type="submit">Düzenle</button>
</form>
<form method = "post" action="<?=base_url("blogs")?>">
    <input name="blog_delete_id" type="hidden" value="<?=$blog['blog_id']?>">
    <button type="submit">Sil</button>
</form>

<?php
?>
<h1>Blog Sayfası</h1>

<form method = "post" action="<?=base_url("blogs")?>">
    <label for="">Blog Adı:</label>
    <input name="blog_title" type="text">
    <label for="">Blog Açıklaması:</label>
    <input name="blog_description" type="text">
    <button type="submit">Ekle</button>
</form>

<table>
    <tr>
        <th>Blog Adı</th>
        <th>Blog Açıklama</th>
        <th>Düzenle</th>
    </tr>

<?php
foreach ($blogs as $blog){
    ?>
    <tr>
        <td><?=$blog['blog_title']?></td>
        <td><?=$blog['blog_description']?></td>
        <td><a href="<?=base_url("blogs/".$blog['blog_id'])?>">Düzenle</a></td>
    </tr>

<?php
}
?>

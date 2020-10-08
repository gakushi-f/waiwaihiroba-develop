<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200906090153 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // 商品レビュー 追加
        $this->addSql("insert into dtb_page(page_name,url,file_name,edit_type,author,description,keyword,create_date,update_date,meta_robots,meta_tags,discriminator_type) values ('MYページ/商品新規レビュー','mypage_review_new','Mypage/review_edit',2,null,null,null,'2020/08/18 20:00:00','2020/08/18 20:00:00','noindex',null,'page')");
        $this->addSql("insert into dtb_page(page_name,url,file_name,edit_type,author,description,keyword,create_date,update_date,meta_robots,meta_tags,discriminator_type) values ('MYページ/商品レビュー(完了)','mypage_review_complete','Mypage/review_complete',2,null,null,null,'2020/08/18 20:00:00','2020/08/18 20:00:00','noindex',null,'page')");
        $this->addSql("insert into dtb_page_layout(page_id,layout_id,sort_no,discriminator_type) values ((SELECT id FROM dtb_page WHERE page_name = 'MYページ/商品新規レビュー'),'2',42,'pagelayout')");
        $this->addSql("insert into dtb_page_layout(page_id,layout_id,sort_no,discriminator_type) values ((SELECT id FROM dtb_page WHERE page_name = 'MYページ/商品レビュー(完了)'),'2',42,'pagelayout')");
    }

    public function down(Schema $schema) : void
    {
        // 商品レビュー 削除
        $this->addSql("delete from dtb_page_layout WHERE page_id = (SELECT id FROM dtb_page WHERE page_name = 'MYページ/商品新規レビュー')");
        $this->addSql("delete from dtb_page_layout WHERE page_id = (SELECT id FROM dtb_page WHERE page_name = 'MYページ/商品レビュー(完了)')");
        $this->addSql("delete from dtb_page WHERE page_name = 'MYページ/商品新規レビュー'");
        $this->addSql("delete from dtb_page WHERE page_name = 'MYページ/商品レビュー(完了)'");
    }
}

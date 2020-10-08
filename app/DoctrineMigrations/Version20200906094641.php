<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200906094641 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // パスワード再設定完了画面 追加
        $this->addSql("insert into dtb_page(page_name,url,file_name,edit_type,author,description,keyword,create_date,update_date,meta_robots,meta_tags,discriminator_type) values ('パスワード再発行(再設定完了ページ)','forgot_reset_complete','Forgot/reset_complete',2,null,null,null,'2020/08/18 20:00:00','2020/08/18 20:00:00','noindex',null,'page')");
        $this->addSql("insert into dtb_page_layout(page_id,layout_id,sort_no,discriminator_type) values ((SELECT id FROM dtb_page WHERE page_name = 'パスワード再発行(再設定完了ページ)'),'2',42,'pagelayout')");
    }

    public function down(Schema $schema) : void
    {
        // パスワード再設定完了画面 削除
        $this->addSql("delete from dtb_page_layout WHERE page_id = (SELECT id FROM dtb_page WHERE page_name = 'パスワード再発行(再設定完了ページ)')");
        $this->addSql("delete from dtb_page WHERE page_name = 'パスワード再発行(再設定完了ページ)'");
    }
}

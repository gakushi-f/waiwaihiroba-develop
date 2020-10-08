<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200919160300 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // 出店者募集 追加
        $this->addSql("insert into dtb_page(page_name,url,file_name,edit_type,author,description,keyword,create_date,update_date,meta_robots,meta_tags,discriminator_type) values ('出店者募集','for_producer','for_producer/index',2,null,null,null,'2020/09/19 16:00:00','2020/09/19 16:00:00','noindex',null,'page')");
        $this->addSql("insert into dtb_page_layout(page_id,layout_id,sort_no,discriminator_type) values ((SELECT id FROM dtb_page WHERE page_name = '出店者募集'),'2',42,'pagelayout')");
        // 出店者登録 追加
        $this->addSql("insert into dtb_page(page_name,url,file_name,edit_type,author,description,keyword,create_date,update_date,meta_robots,meta_tags,discriminator_type) values ('出店者登録','for_producer_form','for_producer/form',2,null,null,null,'2020/09/19 16:00:00','2020/09/19 16:00:00','noindex',null,'page')");
        $this->addSql("insert into dtb_page_layout(page_id,layout_id,sort_no,discriminator_type) values ((SELECT id FROM dtb_page WHERE page_name = '出店者登録'),'2',42,'pagelayout')");
        // 出店者登録(受付完了) 追加
        $this->addSql("insert into dtb_page(page_name,url,file_name,edit_type,author,description,keyword,create_date,update_date,meta_robots,meta_tags,discriminator_type) values ('出店者登録(受付完了)','for_producer_form_complete','for_producer/form_complete',2,null,null,null,'2020/09/19 16:00:00','2020/09/19 16:00:00','noindex',null,'page')");
        $this->addSql("insert into dtb_page_layout(page_id,layout_id,sort_no,discriminator_type) values ((SELECT id FROM dtb_page WHERE page_name = '出店者登録(受付完了)'),'2',42,'pagelayout')");
    }

    public function down(Schema $schema): void
    {
        // 出店者登録 削除
        $this->addSql("delete from dtb_page_layout WHERE page_id = (SELECT id FROM dtb_page WHERE page_name = '出店者募集')");
        $this->addSql("delete from dtb_page WHERE page_name = '出店者募集'");
        // 出店者登録 削除
        $this->addSql("delete from dtb_page_layout WHERE page_id = (SELECT id FROM dtb_page WHERE page_name = '出店者登録')");
        $this->addSql("delete from dtb_page WHERE page_name = '出店者登録'");
        // 出店者登録(受付完了) 削除
        $this->addSql("delete from dtb_page_layout WHERE page_id = (SELECT id FROM dtb_page WHERE page_name = '出店者登録(受付完了)')");
        $this->addSql("delete from dtb_page WHERE page_name = '出店者登録(受付完了)'");
    }
}

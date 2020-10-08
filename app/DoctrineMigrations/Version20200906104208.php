<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200906104208 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // 地域別特色 追加
        $this->addSql("insert into dtb_page(page_name,url,file_name,edit_type,author,description,keyword,create_date,update_date,meta_robots,meta_tags,discriminator_type) values ('地域別特色','regionalcharacteristics','regionalcharacteristics/index',2,null,null,null,'2020/08/18 20:00:00','2020/08/18 20:00:00','noindex',null,'page')");
        $this->addSql("insert into dtb_page_layout(page_id,layout_id,sort_no,discriminator_type) values ((SELECT id FROM dtb_page WHERE page_name = '地域別特色'),'2',42,'pagelayout')");
    }

    public function down(Schema $schema) : void
    {
        // 地域別特色 削除
        $this->addSql("delete from dtb_page_layout WHERE page_id = (SELECT id FROM dtb_page WHERE page_name = '地域別特色')");
        $this->addSql("delete from dtb_page WHERE page_name = '地域別特色'");
    }
}

<?php
namespace Centreon\Domain\Repository;

use Centreon\Infrastructure\CentreonLegacyDB\ServiceEntityRepository;

class CfgResourceRepository extends ServiceEntityRepository
{

    /**
     * Export cfg resources
     * 
     * @param int[] $pollerIds
     * @return array
     */
    public function export(array $pollerIds): array
    {
        // prevent SQL exception
        if (!$pollerIds) {
            return [];
        }

        $ids = join(',', $pollerIds);

        $sql = <<<SQL
SELECT
    t.*
FROM cfg_resource AS t
INNER JOIN cfg_resource_instance_relations AS crir ON crir.resource_id = t.resource_id
WHERE crir.instance_id IN ({$ids})
SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function truncate()
    {
        $sql = <<<SQL
TRUNCATE TABLE `cfg_resource`;
TRUNCATE TABLE `cfg_resource_instance_relations`
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }
}

<?php
namespace Album\Model;
use http\Exception\RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class AlbumTable{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        return $this->tableGateway->select();
    }

    public function getAlbum($id){
        $id = (int) $id;
        $formset = $this->tableGateway->select(['id' => $id]);
        $row = $formset->current();

        if (!$row){
            throw new RuntimeException(sprintf("No se pudo encontrar nada con ese id %d", $id));
        }

        return $row;
    }

    public function saveAlbum(Album $album){
        $data = [
            'artist' => $album->artist,
            'title' => $album->title,
        ];
        $id = (int) $album->id;

        if($id === 0){
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getAlbum($id);
        }catch (RuntimeException $exception){
            throw new RuntimeException(
                sprintf("No se puede actualizar con ese ID %d", $id)
            );
        }

        $this->tableGateway->update($data, ['id'=>$id]);
    }

    public function deleteAlbum($id){
        $this->tableGateway->delete(['id'=>(int)$id]);
    }
}
<?php

use App\Storage\SessionStorage;

/**
 * Void class for the StorageInterface
 * this can be used to "mimic" the correct handling of storage, without actually storing any data ever
 * retrieving items from storage from this class, should never return any data
 * storing data should always pretend it got stored successfully
 */
class VoidStorage implements StorageInterface {

    public function __construct()
    {
        
    }

    public function get(string $key) 
    {
        return "";
    }

    public function set(string $key, $data): bool 
    {
        return true;
    }

    public function delete(string $key): bool
    {
        return true;
    }

    public function update(string $key, $data): bool
    {
        return false;
    }

}


use App\Storage\CookieStorage; 

class Cart
{
    public StorageInterface $storage;

    public function __construct(StorageInterface $storage = new SessionStorage())
    {
        $this->storage = $storage;
    }

    public function addItem(string $item) 
    {
        $this->storage->set("item", $item);
    }

    public function getItem() 
    {
        $this->storage->get("item");
    }
}
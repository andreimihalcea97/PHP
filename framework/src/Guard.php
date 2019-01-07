<?php
namespace Framework;

interface Guard {
    //this function will contain the “guarding” logic
    public function handle(array $params = null);

    //this function will be called when the guarding check fails and will contain the logic to be executed in such a case
    public function reject();
}

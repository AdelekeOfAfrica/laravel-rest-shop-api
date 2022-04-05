<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
        //app()[\Spatie\Permission\PermissionRegister::class]
        //->forgetCachedPermissions();

        

        //available permission for the store 
        $addUser='add user';
        $editUser='edit user';
        $deleteUser='delete user';
        $approveStore ="approve user";
        $suspendStore = 'suspend user';

        $addStore ='add store';
        $editStore ='edit store';
        $deleteStore ='delete store';

        $addProductLine = 'add productline';
        $editProductLine = 'edit productline';
        $deleteProductLine='delete productLine';

        $addBrand = 'add brand';
        $editBrand = 'edit brand';
        $deleteBrand = 'delete brand';

        $addProduct = 'add prduct';
        $editProduct = 'edit product';
        $deleteProduct = 'delete Product';
        $viewProduct ='view product';

        //creates permssion for stores 
        Permission::create(['name'=>$addUser]);
        Permission::create(['name'=>$deleteUser]);
        Permission::create(['name'=>$editUser]);

        //stores approval permission
        Permission::create(['name'=>$approveStore]);
        Permission::create(['name'=>$suspendStore]);

        //manage stores permission
        Permission::create(['name'=>$addStore]);
        Permission::create(['name'=>$editStore]);
        Permission::create(['name'=>$deleteStore]);

        //manage stores brand
        Permission::create(['name'=>$addBrand]);
        Permission::create(['name'=>$editBrand]);
        Permission::create(['name'=>$deleteBrand]);

        //manage productline 
        Permission::create(['name'=>$addProductLine]);
        Permission::create(['name'=>$editProductLine]);
        Permission::create(['name'=>$deleteProductLine]);

        //manage product 
        Permission::create(['name'=>$addProduct]);
        Permission::create(['name'=>$editProduct]);
        Permission::create(['name'=>$deleteProduct]);
        permission::create(['name'=>$viewProduct]);

        //define rolesavailable 
        $superAdmin = 'super_admin';
        $systemAdmin = 'system_admin';
        $storeOwner = 'store_owner';
        $storeAdmin = 'store_admin';
        $customer = 'customer';
         
        
        //assigning of roles
        Role::create(['name'=>$superAdmin])
        ->givePermissionTo(Permission::all());

        Role::create(['name'=>$systemAdmin])->givePermissionTo([
            $addUser,
            $editUser,
            $deleteUser,
            $addStore,
            $editStore,
            $deleteStore,
            $approveStore,
            $suspendStore

        ]);

        
        Role::create(['name'=>$storeOwner])->givePermissionTo([
            $addBrand,
            $editBrand,
            $deleteBrand,
            $addProductLine,
            $editProductLine,
            $deleteProductLine,
            $addStore,
            $editStore,
            $deleteStore,
            $approveStore,
            $suspendStore,
            $addProduct,
            $editProduct,
            $deleteProduct,
            $viewProduct
        ]);

        Role::create(['name'=>$storeAdmin])->givePermissionTo([
            $editProductLine,
            $addStore,
            $editStore,
            $addProduct,
            $editProduct,
            $deleteProduct,
           $editBrand
        ]);

        Role::create(['name'=>$customer])->givePermissionTo([
            $viewProduct
        ]);


    }
}

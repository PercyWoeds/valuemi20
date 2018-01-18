class AddUserToRuc < ActiveRecord::Migration
  def change
    add_column :users, :ruc, :string
    
  end
end

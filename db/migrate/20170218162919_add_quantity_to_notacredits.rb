class AddQuantityToNotacredits < ActiveRecord::Migration
  def change
    add_column :notacredits, :quantity, :float
  end
end
